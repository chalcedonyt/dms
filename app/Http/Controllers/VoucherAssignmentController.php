<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoucherAssignmentController extends Controller
{
    public function validateVoucher(Request $request, string $uuid) {
        return view('vouchers.validate');
    }
}
