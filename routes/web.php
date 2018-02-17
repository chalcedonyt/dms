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

Route::get('/login', function() {
    return view('login');
});
Route::get('login/google', 'Auth\\LoginController@redirectToProvider');
Route::get('oauth/google/callback', 'Auth\\LoginController@handleProviderCallback');

Route::middleware('auth')->group(function(){
    Route::get('/home', function(){
        return view('home');
    });
    Route::get('/list/create', 'MemberListController@create');
    Route::get('/list/import/{spreadsheet_id}/{sheet_id}', 'MemberListController@sheetImport');
});

Route::middleware('auth')->prefix('api')->group(function() {
    Route::get('spreadsheets', 'Api\\GoogleSheetsController@index');
    Route::get('spreadsheets/{spreadsheet_id}/sheets', 'Api\\GoogleSheetsController@sheets');
    Route::get('spreadsheets/{spreadsheet_id}/{sheet_id}', 'Api\\GoogleSheetsController@show')
    ->where('sheet_id', '[0-9]+');

    Route::post('member_lists', 'Api\\MemberListController@store');
});
