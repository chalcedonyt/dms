<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class MemberListTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['members'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\MemberList $list)
    {
        return [
            'id' => $list->id,
            'name' => $list->name,
            'description' => $list->description
        ];
    }

    public function includeMembers(\App\MemberList $list)
    {
        if ($list->members) {
            return $this->collection($list->members, new \App\Transformers\MemberTransformer);
        }
    }
}
