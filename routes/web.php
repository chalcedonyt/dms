<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function() {
    if (\Cookie::has('dms_login')) {
        return redirect('/home');
    }
    return view('login');
});
Route::get('/login', function() {
    return view('login');
});
Route::get('login/google', 'Auth\\LoginController@redirectToProvider');
Route::get('oauth/google/callback', 'Auth\\LoginController@handleProviderCallback');

Route::get('/home', function(){
    return response('Logged in');
});