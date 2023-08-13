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

Route::group(['namespace'=>'\Modules\Mark\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


                Route::get('grade-book/my/paginate','GradeBookController@getMyGradeBooksPaginate');
                Route::get('grade-book/{grade_book_id}','GradeBookController@show');
                Route::match(['get','post'],'grade-book/roster/{roster_id}','GradeBookController@generateGradeBook');
                Route::match(['get','post'],'grade-book/roster/{roster_id}/download','GradeBookController@downloadGradeBook');

                Route::get('mark/roster/{roster_id}/download','StudentMarkController@downloadRosterMarks');

                Route::get('mark/roster-assignment/{roster_assignment_id}','StudentMarkController@getRosterAssignmentMarks');
                Route::get('mark/roster-assignment/{roster_assignment_id}/download','StudentMarkController@downloadRosterAssignmentMarks');

                Route::get('mark/student/{student_id}','StudentMarkController@getStudentMark');
                Route::get('mark/student/{student_id}/download','StudentMarkController@downloadStudentMark');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin')], function () {

        });


    });


});




