<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Member;
use App\MemberList;
use App\MemberListAttribute;
use App\MemberListValue;
use App\Voucher;
use App\VoucherAssignment;

use \DrewM\MailChimp\MailChimp;

class MemberListController extends Controller
{
    protected $mailchimp;

    public function __construct(Mailchimp $mailchimp) {
        $this->mailchimp = $mailchimp;
    }

    // {
    //     "attributes": [{
    //         "name": "My attribute",
    //         "offset": 0
    //     }]
    //     "members": [{
    //         "attributes": [{
    //             "offset": 0,
    //             "value": "My value"
    //         }],
    //         "special": {
    //             "email": "abcdef@email.com",
    //             "name": "My name",
    //             "contact": "My contact"
    //         }
    //     }]

    // }
    public function index(Request $request)
    {
        $lists = \App\MemberList::orderBy('created_at', 'DESC')
        //should possibly put this in a global scope
        ->where('hidden', 0)
        ->limit(20)
        ->offset($request->input('offset', 0))
        ->get();

        $data = fractal()->collection($lists, new \App\Transformers\MemberListTransformer)->toArray();
        return response()->json([
            'member_lists' => $data
        ]);
    }

    public function show(Request $request, $id)
    {
        $list = \App\MemberList::with('members', 'attributes')->find($id);

        $data = fractal()->includeMembers()
        ->item($list, new \App\Transformers\MemberListTransformer)->toArray();
        return response()->json($data);
    }

    public function assignVoucher(Request $request, int $list_id) {
        $list = MemberList::find($list_id);
        $voucher = Voucher::find($request->input('voucher_id'));

        foreach ($request->input('member_ids') as $member_id) {
            VoucherAssignment::create([
                'member_list_id' => $list->getKey(),
                'member_id' => $member_id,
                'voucher_id' => $voucher->getKey(),
                'assigned_by' => \Auth::user()->getKey(),
                'expires_at' => (new \App\Gateways\VoucherGateway)->deriveExpiry($voucher),
                'uuid' => md5(rand().$list->title)
            ]);
        }
        return response()->json([
            'success' => 1,
            'errors' => []
        ]);
    }

    public function delete(Request $request, \App\MemberList $list) {
        $list->hidden = true;
        $list->save();

        return response()->json([
            'success' => 1,
            'errors' => []
        ]);
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'list_title' => 'required|string',
            'attributes' => 'required|array',
            'attributes.*.name' => 'required|distinct',
            'attributes.*.offset' => 'required|integer',
            'members' => 'required|array',
            'members.*.attributes' => 'required|array',
            'members.*.attributes.*.offset' => 'required|integer',
            'members.*.special.email' => 'string|required',
            'members.*.special.name' => 'string|required',
            'members.*.special.contact' => 'string'
        ]);
        //create the list
        $list = MemberList::create([
            'name' => $request->input('list_title')
        ]);

        //create the attributes
        $attrs = collect($request->input('attributes'))->map(function ($attr) use ($list) {
            $attribute = MemberListAttribute::create([
                'member_list_id' => $list->getKey(),
                'attribute_name' => $attr['name']
            ]);
            //temporary property
            $attribute->offset = $attr['offset'];
            return $attribute;
        });

        collect($request->input('members'))->each(function ($m) use ($list, $attrs) {
            //insert new member based on email
            $member = Member::firstOrNew(['email' => $m['special']['email']]);
            $member->contactno = $m['special']['contact'];
            $member->name = $m['special']['name'];
            $member->save();
            $member->memberLists()->attach($list->getKey());

            collect($m['attributes'])->each(function ($attr) use ($list, $member, $attrs) {
                //insert the attribute values
                MemberListValue::create([
                  'member_id' => $member->getKey(),
                  'member_list_id' => $list->getKey(),
                  'member_list_attribute_id' => $attrs->firstWhere('offset', '=', $attr['offset'])->getKey(),
                  'value' => $attr['value']
                ]);
            });
        });

        $data = fractal()->includeMembers()
        ->item($list->load('members', 'attributes'), new \App\Transformers\MemberListTransformer)
        ->toArray();

        return response()->json($data);
    }

    public function mailchimpSync(Request $request, $id)
    {
        $list = MemberList::with('members')->find($id);
        $list_data = fractal()->includeMembers()->item($list, new \App\Transformers\MemberListTransformer)->toArray();
        if (!$list->mailchimp_list_id) {
            $response = $this->mailchimp->post('lists', [
                'name' => $list->name,
                'contact' => config('mailchimp.contact'),
                'permission_reminder' => config('mailchimp.permission_reminder'),
                'campaign_defaults' => config('mailchimp.campaign_defaults'),
                'email_type_option' => false,
                'visibility' => 'prv'
            ]);
            $list->mailchimp_list_id = $response['id'];
            $list->save();
        }

        //create the merge fields
        for ($i = 0; $i < count($list_data['members'][0]['attributes']); $i++ ){
            //cannot use MERGE0 as a name
            $tag = strtoupper(str_slug($list_data['members'][0]['attributes'][$i]['attribute_name']));
            $this->mailchimp->post('lists/'.$list->mailchimp_list_id.'/merge-fields', [
                'tag' => $tag,
                'name' => $list_data['members'][0]['attributes'][$i]['attribute_name'],
                'type' => 'text'
            ]);
        }
        //voucher merge field
        $this->mailchimp->post('lists/'.$list->mailchimp_list_id.'/merge-fields', [
            'tag' => 'VOUCHER',
            'name' => 'Voucher type',
            'type' => 'text'
        ]);
        $this->mailchimp->post('lists/'.$list->mailchimp_list_id.'/merge-fields', [
            'tag' => 'BARCODE',
            'name' => 'Barcode url',
            'type' => 'text'
        ]);

        $member_data = collect($list_data['members'])->map(function ($member) {
            $data = [
                'email_address' => $member['email'],
                'status_if_new' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $member['name'],
                ]
            ];
            for ($i = 0; $i < count($member['attributes']); $i++ ){
                //cannot use MERGE0 as a name
                $tag = strtoupper(str_slug($member['attributes'][$i]['attribute_name']));
                $data['merge_fields'][$tag] = trim($member['attributes'][$i]['value']) ?: '';
            }
            if ($member['voucher_assignment']) {
                $data['merge_fields']['VOUCHER'] = $member['voucher_assignment']['voucher']['title'];
                $data['merge_fields']['BARCODE'] = $member['voucher_assignment']['barcode_url'];
            }
            return $data;
        })->all();
        $response = $this->mailchimp->post('lists/'.$list->mailchimp_list_id, [
            'members' => $member_data,
            'update_existing' => true
        ]);

        $data = [];
        if (isset($response['errors']) && count($response['errors'])) {
            $data['errors'] = collect($response['errors'])->pluck('error');
        } else {
            $data['success'] = true;
        }
        return response()->json($data);
    }
}
