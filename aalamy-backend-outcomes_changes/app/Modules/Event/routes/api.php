<?php

use App\Http\Controllers\Classes\ApplicationModules;
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
$module = ApplicationModules::EVENT_MODULE_NAME;

Route::group(['namespace'=>"\Modules\Event\Http\Controllers",'prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Calendar
                Route::get('calendar/my/by-date','CalendarController@getMyCalendarByDate');
                Route::get('calendar/my-class/{class_id}/by-date','CalendarController@getMyClassCalendarByDate');

                //Event
                Route::get('event/target-me/by-month','EventController@getEventsTargetMeByMonth');
                Route::post('event/create','EventController@store');
                Route::post('event/update/{id}','EventController@update');
                Route::delete('event/delete/{id}','EventController@destroy');



            });

        });

        Route::group(['namespace'=>'\Modules\Event\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Event
            Route::get('event/paginate/{soft_delete?}','Super_EventController@paginate');
            Route::delete('event/{event_id}/delete-or-restore','Super_EventController@softDeleteOrRestore');
            Route::delete('event/{event_id}/force-delete','Super_EventController@destroy');

        });


    });


});




