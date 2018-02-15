<?php

namespace App\Http\Controllers;

use App\MemberList;
use Illuminate\Http\Request;

class MemberListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MemberList  $memberList
     * @return \Illuminate\Http\Response
     */
    public function show(MemberList $memberList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MemberList  $memberList
     * @return \Illuminate\Http\Response
     */
    public function edit(MemberList $memberList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MemberList  $memberList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MemberList $memberList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MemberList  $memberList
     * @return \Illuminate\Http\Response
     */
    public function destroy(MemberList $memberList)
    {
        //
    }
}
