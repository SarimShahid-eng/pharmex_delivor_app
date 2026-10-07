<?php

use Illuminate\Http\Request;

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


Route::namespace('Administrator')->group(function () {
	Route::get('/get_data', 'APIDataController@getAllDatta');
	Route::post('/sync_data', 'APIDataController@SyncData');
    
	Route::post('/customer_order', 'APIDataController@customer_order');
	Route::get('/get_my_order', 'APIDataController@get_my_order');
	Route::get('/order_details', 'APIDataController@order_details');
	
	Route::post('/customer_order_return', 'APIDataController@customer_order_return');
	Route::get('/get_my_order_return', 'APIDataController@get_my_order_return');
	Route::get('/order_return_details', 'APIDataController@order_return_details');
	
	Route::get('/version','APIDataController@androidVersion');
});

// user Routes
Route::post('user/login','AppUserController@login');
Route::prefix('user')->middleware(['basicAuth'])->group(function () {
 
    
    Route::get('/task_statistics','AppUserController@task_statistics');
    Route::get('/task_details','AppUserController@task_details');
    Route::get('/get_data', 'AppUserController@get_All_Datta');

    Route::get('/home', 'AppUserController@home');
    Route::middleware('customerActive')->group(function(){
        Route::post('/customer_order', 'AppUserController@customer_order');
    });
    Route::get('/get_my_order', 'AppUserController@get_my_order');
	Route::get('/order_details', 'AppUserController@order_details');
	Route::get('/customer_order_cancel', 'AppUserController@customer_order_cancel');

    Route::post('/customer_order_return', 'AppUserController@customer_order_return');
    Route::get('/get_my_order_return', 'AppUserController@get_my_order_return');
	Route::get('/order_return_details', 'AppUserController@order_return_details');
    Route::get('/order_return_listing', 'AppUserController@order_return_listing');
    Route::post('/mark_as_lifted', 'AppUserController@mark_as_lifted');

	Route::post('/sync_data', 'AppUserController@SyncData');
    Route::get('/get_expire_date', 'AppUserController@get_expire_date');
    
    Route::get('/get_todat_order', 'AppUserController@get_todat_order');
    
    Route::get('/version','AppUserController@androidVersion');

});