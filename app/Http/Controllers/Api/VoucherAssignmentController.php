<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\VoucherAssignment;
use App\VoucherRedemption;
use App\Voucher;

class VoucherAssignmentController extends Controller
{
    public function validateVoucher(Request $request, $uuid) {
        $va = VoucherAssignment::with('voucher', 'member')->where('uuid', '=', $uuid)->first();
        if (!$va) {
            die("Invalid");
        }

        $vr = VoucherRedemption::create([
            'voucher_assignment_id' => $va->getKey(),
            'voucher_id' => $va->voucher->getKey(),
            'validated_by' => \Auth::user()->getKey()
        ]);
        $data = fractal()
        ->includeValidator()
        ->includeVoucher()
        ->item($vr, new \App\Transformers\VoucherRedemptionTransformer);

        return response()->json($data);
    }

    public function prevalidateVoucher(Request $request, $uuid) {
        $va = VoucherAssignment::with('voucher', 'member')->where('uuid', '=', $uuid)->first();
        if (!$va) {
            die("Invalid");
        }
        $data = fractal()->includeMember()->item($va, new \App\Transformers\VoucherAssignmentTransformer);
        return response()->json($data);
    }
}
