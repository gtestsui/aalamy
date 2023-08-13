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

Route::group(['namespace'=>'\Modules\Address\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


            });

        });


        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),'prefix'=>config('panel.super_admin_api_prefix')], function () {
            //Country
            Route::post('country/create','CountryController@store');
            Route::post('country/update/{id}','CountryController@update');
            Route::delete('country/delete/{id}','CountryController@destroy');


            //State
            Route::post('state/create','StateController@store');
            Route::post('state/update/{id}','StateController@update');
            Route::delete('state/delete/{id}','StateController@destroy');


        });


    });

    //Country
    Route::get('country/all','CountryController@index');
    Route::get('country/all/with-states','CountryController@getCountryWithStates');



});




