<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//

Route::group(['namespace'=>'\Modules\User\Http\Controllers'],function (){

    //use this link for redirect not as api
//    Route::post('auth/redirect/{service}','AuthByServiceController@redirectToService');
//    Route::get('auth/callback/{service}','AuthByServiceController@serviceCallback');

});




