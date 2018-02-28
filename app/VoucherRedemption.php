<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherRedemption extends Model
{
    protected $guarded = [];
    protected $hidden = [];

    public function voucher()
    {
        return $this->belongsTo(\App\Voucher::class);
    }

    public function validator()
    {
        return $this->belongsTo(\App\User::class, 'validated_by');
    }
}
