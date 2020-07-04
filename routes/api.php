<?php

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
    Route::apiResource('/member', 'MemberShipController');
    Route::get('/children/{parrain_id}', 'MemberShipController@getChildren');
    Route::get('/code', 'MemberShipController@generateKey');

    //Transactions Route
    Route::apiResource('/transactions', 'TransactionsController');
    Route::get('/getTransactionReport/{id}', 'TransactionsController@getTransactionReport');

    Route::get('transfer/{id}', 'TransactionsController@transfer');

    // Accounts Routing
    Route::apiResource('/account/registration', 'AccountController');

});
Route::get('getTree/{id}', 'MemberShipController@getTree');








