<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\VoucherAssignment;
use App\VoucherRedemption;
use App\Voucher;

use Carbon\Carbon;

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

        //check if time is valid
        $validity_error = null;
        if ($va->expires_at && Carbon::parse($va->expires_at)->lt(Carbon::now())) {
            $validity_error = 'Expired on '.$va->expires_at;
        }
        //check # of times this can be used
        else if ($va->voucher->usage_limit) {
            $times_validated = $va->voucher->voucherRedemptions()->where('voucher_assignment_id', $va->getKey())->count();
            if ($times_validated >= $va->voucher->usage_limit) {
                $validity_error = sprintf('Has been used %d/%d times', $times_validated, $va->voucher->usage_limit);
            }
        }

        $data = fractal()
        ->includeMember()
        ->includeMemberList()
        ->item($va, new \App\Transformers\VoucherAssignmentTransformer);
        return response()->json([
            'voucher_assignment' => $data,
            'validity_error' => $validity_error
        ]);
    }
}
