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

Route::group(['namespace'=>'\Modules\LearningResource\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::get('learning-resource/download/{learning_resource_id}','LearningResourceController@downloadLearningResource');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('topic-QuestionTypes', 'LearningResourceController@QuestionTypes');

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


                //Topic
                Route::get('topic/my/roots','TopicController@getMyOwnRootTopicsPaginate');
                Route::get('topic/allowed/roots','TopicController@getMyAllowedRootTopicsPaginate');
                Route::get('topic/my/{topic_id}/content','TopicController@getMyOwnContentByTopicId');
                Route::get('topic/allowed/{topic_id}/content','TopicController@getMyAllowedContentByTopicId');
                Route::post('topic/create','TopicController@store');
                Route::post('topic/update/{id}','TopicController@update');
                Route::delete('topic/delete/{id}','TopicController@softDelete');


                //LearningResource
                Route::post('learning-resource/create','LearningResourceController@store');
                Route::post('learning-resource/update/{id}','LearningResourceController@update');
                Route::delete('learning-resource/delete/{id}','LearningResourceController@softDelete');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\LearningResource\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {

            //LearningResource
            Route::get('learning-resource/paginate/{soft_delete?}','Super_LearningResourceController@paginate');
            Route::delete('learning-resource/{learning_resource_id}/delete-or-restore','Super_LearningResourceController@softDeleteOrRestore');
            Route::delete('learning-resource/{learning_resource_id}/force-delete','Super_LearningResourceController@destroy');


            //Topic
            Route::get('topic/paginate/{soft_delete?}','Super_TopicController@paginate');
            Route::get('topic/by-id/{id}/{soft_delete}','Super_TopicController@getElementByIdEvenItsDeleted');
            Route::get('topic/root/paginate/{soft_delete?}','Super_TopicController@rootPaginate');
            Route::get('topic/{topic_id}/content/{soft_delete?}','Super_TopicController@getContent');
            Route::delete('topic/{topic_id}/delete-or-restore','Super_TopicController@softDeleteOrRestore');
            Route::delete('topic/{topic_id}/force-delete','Super_TopicController@destroy');

        });


    });


});




