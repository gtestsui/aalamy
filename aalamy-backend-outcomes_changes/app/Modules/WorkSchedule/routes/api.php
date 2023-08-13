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

Route::group(['namespace'=>'\Modules\WorkSchedule\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //WorkSchedule
                Route::post('work-schedule/class/{class_id}/create-period','WorkScheduleClassController@storeOrUpdate');
                Route::get('work-schedule/class/{class_id}','WorkScheduleClassController@getByClassIdForCreator');
                Route::get('work-schedule/for-reader','WorkScheduleClassController@getWorkScheduleForReader');
                Route::delete('work-schedule/{id}','WorkScheduleClassController@destroy');


            });

        });

        Route::group(['namespace'=>'\Modules\WorkSchedule\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Roster


        });


    });


});




