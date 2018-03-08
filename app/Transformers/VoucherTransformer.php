<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class VoucherTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\Voucher $voucher)
    {
        $data = [
            'id' => $voucher->getKey(),
            'title' => $voucher->title,
            'description' => $voucher->description,
            'usage_limit' => $voucher->usage_limit
        ];
        if ($voucher->expires_at) {
            $data['expiry_type'] = 'Fixed';
            $data['expires_at'] = $voucher->expires_at;
        }
        if ($voucher->expires_days) {
            $data['expiry_type'] = 'Days after issuing';
            $data['expires_days'] = $voucher->expires_days;
        }
        $data['redemption_count'] = $voucher->voucherRedemptions->count();
        return $data;
    }
}
