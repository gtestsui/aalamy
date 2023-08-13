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

Route::group(['namespace'=>'\Modules\DiscussionCorner\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    //File
    Route::get('discussion-corner/post/file/{file_id}/download','PostController@downloadPostFile');


    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

//                //File
//                Route::get('discussion-corner/post/file/{file_id}/download','PostController@downloadPostFile');


                //Post
                Route::get('discussion-corner/post/waiting-approve/paginate','PostController@getWaitingApprove');
                Route::get('discussion-corner/post/waiting-approve/paginate','PostController@getWaitingApprove');
                Route::get('discussion-corner/post/{id}','PostController@show');
                Route::get('discussion-corner/post/random/paginate','PostController@getRandomPaginate');
                Route::post('discussion-corner/post/create','PostController@store');
                Route::post('discussion-corner/post/update/{id}','PostController@update');
                Route::post('discussion-corner/post/approve/{id}','PostController@approve');
                Route::delete('discussion-corner/post/delete/{id}','PostController@softDelete');
                Route::delete('discussion-corner/post/force-delete/{id}','PostController@destroy');

                //Reply
                Route::get('discussion-corner/post/{postId}/reply/paginate','ReplyController@getByPostIdPaginate');
                Route::post('discussion-corner/reply/create','ReplyController@store');
                Route::post('discussion-corner/reply/update/{id}','ReplyController@update');
                Route::delete('discussion-corner/reply/delete/{id}','ReplyController@destroy');


                //Survey
                Route::get(   'discussion-corner/survey/waiting-approve/paginate','SurveyController@getWaitingApprove');
                Route::get(   'discussion-corner/survey/random/paginate','SurveyController@getRandomPaginate');
                Route::get(   'discussion-corner/survey/my/paginate','SurveyController@getMySurveysPaginate');
                Route::get(   'discussion-corner/survey/{id}','SurveyController@show');
                Route::get(   'discussion-corner/survey/{id}/with-all-users-answers','SurveyController@showWithAnswers');
                Route::get(   'discussion-corner/survey/{id}/user/{user_id}/answers','SurveyController@showWithAnswersByUserId');
                Route::post(  'discussion-corner/survey/create','SurveyController@store');
                Route::post(  'discussion-corner/survey/update/{id}','SurveyController@update');
                Route::delete('discussion-corner/survey/delete/{id}','SurveyController@softDelete');
                Route::delete('discussion-corner/survey/force-delete/{id}','SurveyController@destroy');
                Route::post(  'discussion-corner/survey/approve/{id}','SurveyController@approve');

                //SurveyAnswer
                Route::get(   'discussion-corner/survey/question/{question_id}/user-answer/paginate','SurveyAnswerController@getUserAnswersByQuestionId');
                Route::get(   'discussion-corner/survey/choice/{choice_id}/user-answer/paginate','SurveyAnswerController@getUserAnswersByChoiceId');

                Route::post(  'discussion-corner/survey/{survey_id}/answer/create','SurveyAnswerController@store');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\DiscussionCorner\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function ()
        {

            //Post
            Route::get('post/paginate/{soft_delete?}','Super_PostController@paginate');
            Route::get('post/display/{post_id}','Super_PostController@show');
            Route::delete('post/{post_id}/delete-or-restore','Super_PostController@softDeleteOrRestore');
            Route::delete('post/{post_id}/force-delete','Super_PostController@destroy');

            //Reply
            Route::get('post/{post_id}/replies/paginate/{soft_delete?}','Super_ReplyController@getByPostIdPaginate');
            Route::delete('reply/{reply_id}/delete-or-restore','Super_ReplyController@softDeleteOrRestore');
            Route::delete('reply/{reply_id}/force-delete','Super_ReplyController@destroy');

            //Survey
            Route::get('survey/paginate/{soft_delete?}','Super_SurveyController@paginate');
            Route::get('survey/display/{survey_id}/with-answers','Super_SurveyController@showWithAnswers');
            Route::get('survey/question/{question_id}/users-answers/paginate','Super_SurveyController@getUserAnswersByQuestionId');
            Route::get('survey/choice/{choice_id}/users-answers/paginate','Super_SurveyController@getUserAnswersByChoiceId');
            Route::delete('survey/{survey_id}/delete-or-restore','Super_SurveyController@softDeleteOrRestore');
            Route::delete('survey/{survey_id}/force-delete','Super_SurveyController@destroy');


        });


    });


});




