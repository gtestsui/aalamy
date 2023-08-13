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

Route::group(['namespace'=>'\Modules\FlashCard\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //FlashCard
                Route::get('flash-card/by/assignment/{assignment_id}','FlashCardController@getByAssignmentId');
                Route::post('flash-card/create','FlashCardController@store');
                Route::post('flash-card/update/{id}','FlashCardController@update');
                Route::delete('flash-card/delete/{id}','FlashCardController@softDelete');
                Route::delete('flash-card/force-delete/{id}','FlashCardController@destroy');

                //Card
                Route::post('flash-card/{flash_card_id}/card/create','CardController@store');
                Route::post('flash-card/card/update/{id}','CardController@update');
                Route::delete('flash-card/card/delete/{id}','CardController@destroy');

                //Quiz
                Route::post('flash-card/{flash_card_id}/quiz/check','QuizController@checkQuizAnswers');
            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin')], function () {

        });


    });


});




