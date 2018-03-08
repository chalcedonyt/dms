<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Voucher;
use App\VoucherRedemption;

class VoucherRedemptionController extends Controller
{
    public function index(Request $request, Voucher $voucher)
    {
        $redemptions = VoucherRedemption::with('voucherAssignment','voucherAssignment.member','voucher')
        ->where('voucher_id', '=', $voucher->getKey())
        ->get();

        return response()->json([
            'voucher' => fractal()->item($voucher, new \App\Transformers\VoucherTransformer)->toArray(),
            'redemptions' => fractal()
            ->includeValidator()
            ->includeMember()
            ->collection($redemptions, new \App\Transformers\VoucherRedemptionTransformer)->toArray()
        ]);
    }
}
