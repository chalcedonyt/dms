<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class VoucherAssignmentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['voucher'];
    protected $availableIncludes = ['member'];
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\VoucherAssignment $va)
    {
        return [
            'created_at' => \Carbon\Carbon::parse($va->created_at)->format('d M, Y'),
            'expires_at' => \Carbon\Carbon::parse($va->expires_at)->format('d M, Y'),
            'uuid' => $va->uuid,
            'barcode_url' => config('dms.barcode_url').$va->uuid
        ];
    }

    public function includeVoucher(\App\VoucherAssignment $va) {
        if ($va->voucher) {
            return $this->item($va->voucher, new VoucherTransformer);
        }
    }

    public function includeMember(\App\VoucherAssignment $va) {
        if ($va->member) {
            return $this->item($va->member, new MemberTransformer);
        }
    }
}
