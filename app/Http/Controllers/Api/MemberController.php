<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Member;

class MemberController extends Controller
{
    public function index(Request $request) {
        $members = Member::with('memberLists')->orderBy('name')
        ->get();

        $data = fractal()
        ->includeMemberLists()
        ->collection($members, new \App\Transformers\MemberTransformer, 'members');
        return response()->json($data);
    }
}
