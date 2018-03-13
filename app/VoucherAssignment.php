<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherAssignment extends Model
{
    protected $guarded = [];

    public function scopeBelongsToListMember($query, $list_id, $member_id) {
        return $query->join('member_lists', 'member_lists.id', '=', 'voucher_assignments.member_list_id')
        ->where('voucher_assignments.member_id', '=', $member_id)
        ->where('voucher_assignments.member_list_id', '=', $list_id);
    }

    public function voucher() {
        return $this->belongsTo(\App\Voucher::class);
    }

    public function member() {
        return $this->belongsTo(\App\Member::class);
    }

    public function memberList() {
        return $this->belongsTo(\App\MemberList::class);
    }
}
