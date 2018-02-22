<?php
namespace App\Gateways;

use App\Voucher;

class VoucherGateway
{
    public function deriveExpiry(Voucher $v): \Datetime {
        if ($v->expires_at) {
            return $v->expires_at;
        }
        else return \Carbon\Carbon::now()->addDays($v->expires_days)->endOfDay();
    }
}