<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = [];

    public function voucherRedemptions()
    {
        return $this->hasMany(\App\VoucherRedemption::class);
    }
}
