<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Member;
use App\MemberList;
use App\MemberListAttribute;
use App\MemberListValue;

class MemberListController extends Controller
{
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
}
