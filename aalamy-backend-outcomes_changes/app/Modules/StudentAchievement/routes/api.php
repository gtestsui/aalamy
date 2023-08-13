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

Route::group(['namespace'=>'\Modules\StudentAchievement\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //StudentAchievement
                Route::get('student-achievement/{id}','StudentAchievementController@show');
                Route::get('student-achievement/waiting/to-publish','StudentAchievementController@getMyStudentAchievementWaitingToPublish');
                Route::get('student-achievement/my/all','StudentAchievementController@getMyAchievementsAsStudent');
                Route::post('student-achievement/{id}/publish','StudentAchievementController@publish');
                Route::post('student-achievement/create','StudentAchievementController@store');
                Route::post('student-achievement/update/{id}','StudentAchievementController@update');
                Route::delete('student-achievement/delete/{id}','StudentAchievementController@destroy');

            });

        });

        Route::group(['namespace'=>'\Modules\StudentAchievement\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //StudentAchievement
            Route::get('student-achievement/paginate/{soft_delete?}','Super_StudentAchievementController@paginate');
            Route::delete('student-achievement/{student_achievement_id}/delete-or-restore','Super_StudentAchievementController@softDeleteOrRestore');
            Route::delete('student-achievement/{student_achievement_id}/force-delete','Super_StudentAchievementController@destroy');

        });


    });


});




