<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Member;
use App\MemberList;
use App\MemberListAttribute;
use App\MemberListValue;
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
            return $data;
        })->all();

        $response = $this->mailchimp->post('lists/'.$list->mailchimp_list_id, [
            'members' => $member_data,
            'update_existing' => true
        ]);

        $data = [];
        if ($response['errors'] && count($response['errors'])) {
            $data['errors'] = collect($response['errors'])->pluck('error');
        } else {
            $data['success'] = true;
        }
        return response()->json($data);
    }
}
