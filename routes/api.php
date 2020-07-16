<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionsController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\MemberResource  as MemberResource;
use App\Models\Membership;
use App\Models\Transactions;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'member'], function () {
    //Member route
    Route::apiResource('/member', 'Api\MemberShipsController');
    Route::get('/children/{parrain_id}', 'Api\MemberShipsController@getChildren');
    Route::get('/code', 'Api\MemberShipsController@generateKey');

    //Transactions Route
    Route::apiResource('/transactions', 'Api\TransactionsController');
    Route::get('/getTransactionReport/{id}', 'Api\TransactionsController@getTransactionReport');

    Route::put('transfer/{id}', 'Api\TransactionsController@transfer');

    // Accounts Routing
    Route::apiResource('/account', 'Api\AccountsController');
    Route::get('accounts/{account}/enable', 'Api\AccountsController@enable')->name('accounts.enable');
    Route::get('accounts/{account}/disable', 'Api\AccountsController@disable')->name('accounts.disable');

    Route::post('accounts/balance' , 'Api\AccountsController@getBalance');
    
});
    Route::get('getTree/{id}', 'Api\MemberShipsController@getTree');
    Route::get('getStatus/{id}', 'Api\AccountsController@getStatus');


    Route::any('/pay', 'Api\PaymentsController@index');
    Route::any('/success', 'APi\PaymentsController@success');
    Route::POST('/fail', 'Api\PaymentsController@fail');
    Route::POST('/cancel', 'Api\PaymentsController@cancel');
    Route::POST('/pin', 'Api\PaymentsController@pin');









