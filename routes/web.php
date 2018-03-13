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

Route::get('/', function() {
    return view('login');
});
Route::get('login', function() {
    \Session::reflash();
    return view('login');
})->name('login');
Route::get('logout', 'Auth\\LoginController@logout');
Route::get('login/google', 'Auth\\LoginController@redirectToProvider');
Route::get('oauth/google/callback', 'Auth\\LoginController@handleProviderCallback');

Route::middleware('auth.redirect')->group(function() {
    Route::get('/bc/{uuid}', 'VoucherAssignmentController@validateVoucher');
});
Route::middleware('auth')->group(function(){
    Route::get('/home', function(){
        return view('home');
    });
    Route::get('/members', function() {
        return view('members.index');
    });

    Route::get('/lists', 'MemberListController@index')->name('lists');
    Route::get('/list/create', 'MemberListController@create')->name('list-create');
    Route::get('/list/{id}', 'MemberListController@show');
    Route::get('/list/import/{spreadsheet_id}/{sheet_id}', 'MemberListController@sheetImport');

    Route::get('/vouchers', function() {
        return view('vouchers.index');
    })->name('vouchers');
    Route::get('/voucher/create', function() {
        return view('vouchers.create');
    })->name('vouchers-create');
    Route::get('/voucher/{id}/redemptions', function() {
        return view('vouchers.redemptions');
    })->name('vouchers-history');

    Route::get('/admins', function() {
        return view('admins.index');
    });
    Route::get('/admin/create', function() {
        return view('admins.create');
    });
});

Route::middleware('auth')->prefix('api')->group(function() {
    Route::get('members', 'Api\\MemberController@index');

    Route::get('spreadsheets', 'Api\\GoogleSheetsController@index');
    Route::get('spreadsheets/{spreadsheet_id}/sheets', 'Api\\GoogleSheetsController@sheets');
    Route::get('spreadsheets/{spreadsheet_id}/{sheet_id}', 'Api\\GoogleSheetsController@show')
    ->where('sheet_id', '[0-9]+');

    Route::post('member_lists', 'Api\\MemberListController@store');
    Route::get('member_lists', 'Api\\MemberListController@index');
    Route::get('member_list/{id}', 'Api\\MemberListController@show');
    Route::delete('member_list/{list}', 'Api\\MemberListController@delete');
    Route::post('member_list/{id}/assign-voucher', 'Api\\MemberListController@assignVoucher');
    Route::post('member_list/{id}/mailchimp-sync', 'Api\\MemberListController@mailchimpSync');
    Route::post('member_list/{member_list}/remove-members', 'Api\\MemberListController@removeMembers');

    Route::post('vouchers', 'Api\\VoucherController@store');
    Route::get('vouchers', 'Api\\VoucherController@index');
    Route::get('voucher-validate/{uuid}', 'Api\\VoucherAssignmentController@prevalidateVoucher');
    Route::post('voucher-validate/{uuid}', 'Api\\VoucherAssignmentController@validateVoucher');

    Route::get('voucher/{voucher}/redemptions', 'Api\\VoucherRedemptionController@index');

    Route::get('users', 'Api\\UserController@index');
    Route::delete('user/{user}', 'Api\\UserController@destroy');
    Route::put('user/{user}', 'Api\\UserController@update');
    Route::post('user', 'Api\\UserController@store');
});

