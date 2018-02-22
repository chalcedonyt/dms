<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\VoucherAssignment;

class VoucherAssignmentController extends Controller
{
    public function validateVoucher(Request $request, $uuid) {
        $va = VoucherAssignment::with('voucher', 'member')->where('uuid', '=', $uuid)->first();
        if (!$va) {
            die("Invalid");
        }
        $data = fractal()->includeMember()->item($va, new \App\Transformers\VoucherAssignmentTransformer);
        return response()->json($data);
    }
}
