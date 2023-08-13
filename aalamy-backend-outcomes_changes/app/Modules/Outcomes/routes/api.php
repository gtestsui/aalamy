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

Route::group(['namespace'=>'\Modules\Outcomes\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Marks
//                Route::get('class/{class_id}/subject/{subject_id}/students-marks','MarkController@getStudentsMarksByClassIdAndSubjectId');
                Route::get('students-marks/class/{class_id}/subject/{subject_id}','MarkController@getStudentsMarksByClassIdAndSubjectId');
                Route::post('students-marks/{mark_id}/update','MarkController@update');
//                Route::post('students-marks/class/{class_id}/subject/{subject_id}/update','MarkController@update');


                //YearGradeTemplate
                Route::get('year-grades/class/{class_id}/writable-subjects','YearGradeController@getWritableSubjectByBaseLevel');
                Route::get('year-grades/level/{level_id}/student/{student_id}','YearGradeController@getForDefinedStudent');
                Route::post('year-grades/level/{level_id}/student/{student_id}/general-info/update','YearGradeController@updateGeneralInfo');
                Route::get('year-grades/level/{level_id}/student/{student_id}/general-info','YearGradeController@getYearGradeGeneralInfo');
                Route::post('year-grades/{year_grade_template_id}/level/{level_id}/student/{student_id}/data/update','YearGradeController@updateWritableYearGradeData');
                Route::get('students-marks/class/{class_id}/year-grade-template/{year_grade_template_id}','YearGradeController@getStudentsWithMarksInWritableSubject');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\Outcomes\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {

                Route::get('year-grades/level/{level_id}/student/{student_id}','Super_YearGradeController@getForDefinedStudent');
                Route::get('year-grades/student/{student_id}','Super_YearGradeController@getForDefinedStudentByActiveClass');



        });


    });


});




