<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = [];

    public function memberLists()
    {
        return $this->belongsToMany(\App\MemberList::class, 'members_lists_pivot');
    }
}
