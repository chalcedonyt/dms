<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Voucher;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = Voucher::with('voucherRedemptions')
        ->orderBy('created_at', 'DESC')
        ->limit(30)
        ->get();

        $data = fractal()->collection($vouchers, new \App\Transformers\VoucherTransformer, 'vouchers')->toArray();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $expires_at = $request->input('expires_at', null);
        if ($expires_at)
            $expires_at = \Carbon\Carbon::parse($expires_at)->endOfDay();

        $voucher = Voucher::create([
            'title' => $request->input('title', 'Untitle Voucher'),
            'description' => $request->input('description', ''),
            'expires_days' => $request->input('expires_days', 0),
            'expires_at' => $expires_at,
            'usage_limit' => $request->input('usage_limit', 0)
        ]);
        $data = fractal()->item($voucher, new \App\Transformers\VoucherTransformer)->toArray();
        return response()->json($data);
    }
}
