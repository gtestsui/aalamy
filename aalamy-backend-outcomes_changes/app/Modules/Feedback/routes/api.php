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

Route::group(['namespace'=>'\Modules\Feedback\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Feedback
                Route::get('feedback-about-student/my-feedback/paginate','FeedbackAboutStudentController@getMyFeedbackPaginate');
                Route::get('feedback-about-student/my-childes-feedback/all','FeedbackAboutStudentController@getMyChildesFeedback');
                Route::post('feedback-about-student/create','FeedbackAboutStudentController@store');
                Route::post('feedback-about-student/update/{id}','FeedbackAboutStudentController@update');
                Route::delete('feedback-about-student/force-delete/{id}','FeedbackAboutStudentController@destroy');

            });

        });

        Route::group(['namespace'=>'\Modules\Feedback\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Feedback
            Route::get('feedback/paginate/{soft_delete?}','Super_FeedbackAboutStudentController@paginate');
            Route::delete('feedback/{feedback_id}/delete-or-restore','Super_FeedbackAboutStudentController@softDeleteOrRestore');
            Route::delete('feedback/{feedback_id}/force-delete','Super_FeedbackAboutStudentController@destroy');

        });


    });


});




