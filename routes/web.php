<?php

use Illuminate\Support\Facades\Route;

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
    return view('index');
});
Route::get('/login', function () {
    return view('login');
});


Route::get('/donate', function () {
    return view('donate');
});
Route::post('login', 'UserController@authenticate');

Route::post('api/v1/transactions', 'TransactionsController@index');

Route::get('redirect','TransactionsController@redirect');
Route::get('payment/status','TransactionsController@QueryPaymentStatus ');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('fetch/payments', 'TransactionsController@fetchPayments');
        });


