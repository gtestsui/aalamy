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

Route::group(['namespace'=>'\Modules\ContactUs\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


            });

            //ContactUs
            Route::post('contact-us','ContactUsController@store');
        });

        Route::group(['namespace'=>'\Modules\ContactUs\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {
            //ContactUs
            Route::get('contact-us/paginate','Super_ContactUsController@paginate');
            Route::delete('contact-us/force-delete/{id}','Super_ContactUsController@destroy');
        });


    });


});




