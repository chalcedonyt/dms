<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberList extends Model
{
    protected $guarded = [];

    public function members()
    {
        return $this->belongsToMany(\App\Member::class, 'members_lists_pivot');
    }

    public function attributes()
    {
        return $this->hasMany(\App\MemberListAttribute::class);
    }
}
