<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['memberLists'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\Member $member)
    {
        $data = [
            'id' => $member->getKey(),
            'name' => $member->name,
            'email' => $member->email,
            'contactno' => $member->contactno,
            'created_at' => \Carbon\Carbon::parse($member->created_at)->format('d M, Y')
        ];

        if ($member->pivot && get_class($member->pivot->pivotParent) == \App\MemberList::class) {
            $list = \App\MemberList::with('attributes')->find($member->pivot->member_list_id);
            if ($list->attributes) {
                $data['attributes'] = $list->attributes->map(function ($attr) use ($member, $list) {
                    $ml_value = \App\MemberListValue::where('member_id', $member->getKey())
                    ->where('member_list_id', $list->getKey())
                    ->where('member_list_attribute_id', $attr->getKey())
                    ->first();

                    $attr->value = $ml_value ? $ml_value->value : null;
                    return $attr;
                })->toArray();
            }
            $voucher_assignment = \App\VoucherAssignment::with('voucher')
            ->belongsToListMember($list->getKey(), $member->getKey())
            ->get()
            ->first();
            $data['voucher_assignment'] = null;
            if ($voucher_assignment) {
                $data['voucher_assignment'] = fractal()->item($voucher_assignment, new \App\Transformers\VoucherAssignmentTransformer)->toArray();
            }
        }
        return $data;
    }

    public function includeMemberLists($member)
    {
        if ($member->memberLists) {
            return $this->collection($member->memberLists, new \App\Transformers\MemberListTransformer);
        }
    }
}
