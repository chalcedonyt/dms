<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\VoucherRedemption;

class VoucherRedemptionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'member',
        'validator',
        'voucher',
    ];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($vr)
    {
        return $vr->toArray();
    }

    public function includeVoucher($vr) {
        if ($vr->voucher) {
            return $this->item($vr->voucher, new VoucherTransformer);
        }
    }

    public function includeValidator($vr) {
        if ($vr->validator) {
            return $this->item($vr->validator, new UserTransformer);
        }
    }

    public function includeMember($vr) {
        if ($vr->voucherAssignment) {
            return $this->item($vr->voucherAssignment->member, new MemberTransformer);
        }
    }
}
