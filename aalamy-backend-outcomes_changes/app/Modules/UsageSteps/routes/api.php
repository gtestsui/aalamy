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

Route::group(['namespace'=>'\Modules\UsageSteps\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //LastCompletedStep
                Route::get('step/my/last-completed-step-index','UserCompletedStepsController@getMyLastCompletedStep');
                Route::post('step/my/last-completed-step-index/update','UserCompletedStepsController@updateLastCompletedStep');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\UsageSteps\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {

            //Level

        });


    });


});




