<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberListValue extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(\App\Member::class);
    }

    public function memberList()
    {
        return $this->belongsTo(\App\MemberList::class);
    }

    public function memberListAttribute()
    {
        return $this->belongsTo(\App\MemberListAttribute::class);
    }
}
