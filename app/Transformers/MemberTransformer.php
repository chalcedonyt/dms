<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MemberTransformer extends TransformerAbstract
{
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
            'contactno' => $member->contactno
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
        }
        return $data;
    }
}
