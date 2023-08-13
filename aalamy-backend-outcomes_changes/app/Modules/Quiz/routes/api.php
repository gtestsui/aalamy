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

Route::group(['namespace'=>"\Modules\Quiz\Http\Controllers",'prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Quiz
                Route::get('quiz/by-roster/{roster_id}/all','QuizController@getByRosterId');
                Route::get('quiz/by-roster/{roster_id}/for-generate-grade-book/all','QuizController@getByRosterIdForGenerateGradeBook');
                Route::get('quiz/my/finished/by-roster/{roster_id}','QuizController@getMyFinishedStudentQuizzesWithMarksByRosterId');
                Route::get('quiz/my/finished','QuizController@getMyFinishedStudentQuizzesWithMarksAll');
                Route::get('quiz/coming-quizzes/by-roster/{roster_id}/all','QuizController@getComingQuizzesByRosterId');
                Route::get('quiz/coming-quizzes/all','QuizController@getAllMyComingQuizzes');
                Route::get('quiz/{id}/as-owner','QuizController@showForOwner');
                Route::get('quiz/{id}','QuizController@showForStudent');
                Route::get('quiz/{id}/details','QuizController@getQuizInfoForStudent');
                Route::post('quiz/manually/create','QuizController@createNewQuizManually');
                Route::post('quiz/generate','QuizController@generateRandomQuiz');
                Route::delete('quiz/delete/{id}','QuizController@softDelete');


                //QuizAnswer
//                Route::get('quiz/test/test','QuizStudentAnswersController@test');
                Route::get('quiz/{quiz_id}/student/{student_id}/answers','QuizStudentAnswersController@showStudentQuizAnswers');
                Route::get('quiz/{quiz_id}/students','QuizStudentAnswersController@getStudentsMarksByQuizId');
                Route::post('quiz/{quiz_id}/start','QuizStudentAnswersController@startQuiz');
                Route::post('quiz/{quiz_id}/end','QuizStudentAnswersController@endQuiz');
                Route::post('quiz/{quiz_id}/answer','QuizStudentAnswersController@storeOrUpate');


            });

        });

        Route::group(['namespace'=>'\Modules\Quiz\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Quiz
            Route::get('quiz/paginate/{soft_delete?}','Super_QuizController@paginate');
            Route::get('quiz/{quiz_id}/student/marks','Super_QuizController@getStudentsMarksByQuizId');
            Route::delete('quiz/{quiz_id}/delete-or-restore','Super_QuizController@softDeleteOrRestore');
            Route::delete('quiz/{quiz_id}/force-delete','Super_QuizController@destroy');

        });


    });


});




