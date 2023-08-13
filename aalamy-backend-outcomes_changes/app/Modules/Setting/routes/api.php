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

Route::group(['namespace'=>'\Modules\Setting\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){


    Route::get('logo','SettingController@getLogo');


    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\Setting\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {


            Route::group(['prefix' => 'setting'],function(){
                //DeleteDataSetting
                Route::get('delete-data/get','DeleteDataSettingController@show');
                Route::post('delete-data/update','DeleteDataSettingController@update');

                //Setting
                Route::get('logo/get','SettingController@show');
                Route::post('logo/update','SettingController@update');

                //YearSetting
                Route::get('year-setting','YearSettingController@get');
                Route::post('year-setting/update','YearSettingController@update');

            });



        });


    });


});




