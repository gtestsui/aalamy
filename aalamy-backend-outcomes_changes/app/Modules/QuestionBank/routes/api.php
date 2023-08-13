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

Route::group(['namespace'=>'\Modules\QuestionBank\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //QuestionBank
                Route::match(['get','post'],'question-bank/my/paginate','QuestionBankController@getMyQuestionPaginate');
                Route::match(['get','post'],'question-bank/by-ids/get','QuestionBankController@getQuestionsByIds');
                Route::get('question-bank/{id}','QuestionBankController@show');
                Route::match(['get','post'],'question-bank/my/filter','QuestionBankController@QuestionTypes');
                Route::post('question-bank/{id}/share/with-library','QuestionBankController@shareWithLibrary');
                Route::post('question-bank/create','QuestionBankController@store');
                Route::post('question-bank/update/{id}','QuestionBankController@update');
                Route::delete('question-bank/delete/{id}','QuestionBankController@softDelete');
                Route::post('question-bank/force-delete/{id}','QuestionBankController@destroy');


                //QuestionLibrary
                Route::match(['get','post'],'question-library/my/paginate','QuestionLibraryController@getMyQuestionPaginate');
                Route::match(['get','post'],'question-library/allowed/paginate','QuestionLibraryController@getMyAllowedQuestionPaginate');
                Route::get('question-library/{id}','QuestionLibraryController@show');
                Route::post('question-library/update/{id}','QuestionLibraryController@update');

            });

        });

        Route::group(['namespace'=>'\Modules\QuestionBank\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //QuestionBank
            Route::match(['get','post'],'question-bank/paginate/{soft_delete?}','Super_QuestionBankController@paginate');
            Route::get('question-bank/{question_id}/details','Super_QuestionBankController@getQuestionDetails');
            Route::match(['get','post'],'educator/{educator_id}/question-bank/paginate/{soft_delete?}','Super_QuestionBankController@getEducatorQuestionsBankPaginate');
            Route::match(['get','post'],'school/{school_id}/question-bank/paginate/{soft_delete?}','Super_QuestionBankController@getSchoolQuestionsBankPaginate');
            Route::delete('question-bank/{question_bank_id}/delete-or-restore','Super_QuestionBankController@softDeleteOrRestore');
            Route::delete('question-bank/{question_bank_id}/force-delete','Super_QuestionBankController@destroy');


        });


    });


});




