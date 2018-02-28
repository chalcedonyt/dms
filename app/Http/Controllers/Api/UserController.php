<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        $data = fractal()->collection($users, new \App\Transformers\UserTransformer, 'users');
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->email = $request->input('email');
        $user->name = '[Has not logged in]';
        $user->password = '';
        $user->role_id = $request->input('role_id');
        $user->save();
        $data = fractal()->item($user, new \App\Transformers\UserTransformer);
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \App\User $user)
    {
        $user->role_id = $request->input('role_id');
        $user->save();
        $data = fractal()->item($user, new \App\Transformers\UserTransformer);
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\User $user)
    {
        $user->delete();
        return response()->json([
            'success' => 1
        ]);
    }
}
