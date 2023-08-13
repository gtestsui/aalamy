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

Route::group(['namespace'=>'\Modules\RosterAssignment\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


                //RosterAssignment
                Route::get('roster-assignment/my/paginate','RosterAssignmentController@getMyRosterAssignmentPaginate');
                Route::get('roster-assignment/my/by-level-subject/paginate','RosterAssignmentController@getByLevelSubjectIdPaginate');
                Route::get('roster-assignment/student/{student_id}/by-level-subject/paginate','RosterAssignmentController@getByLevelSubjectIdByStudentForParentPaginate');
                Route::get('roster-assignment/student/{student_id}/between-tow-dates/paginate','RosterAssignmentController@getStudentRosterAssignmentsBetweenTowDatesPaginate');
                Route::get('roster-assignment/{roster_assignment_id}','RosterAssignmentController@show');
                Route::get('roster-assignment/{roster_assignment_id}/student/{student_id}/for-parent','RosterAssignmentController@showForParent');
                Route::get('roster-assignments/by-assignment/{assignment_id}','RosterAssignmentController@getByAssignmentId');
                Route::get('roster-assignments/by-roster/{roster_id}','RosterAssignmentController@getByRosterId');
                Route::get('roster-assignments/by-roster/{roster_id}/for-generate-grade-book','RosterAssignmentController@getByRosterIdForGenerateGradeBook');
                Route::post('assignment/add-to-roster','RosterAssignmentController@store');
                Route::post('roster-assignment/{student_user_id}/{roster_assignment_id}/download','RosterAssignmentController@mergeRosterAssignmentPdfsAndDownload');
                Route::post('assignment/add-to-many-rosters','RosterAssignmentController@addAssignmentToManyRosters');
                Route::post('roster/link-to-many-assignments','RosterAssignmentController@linkRosterToManyAssignments');
                Route::post('roster-assignment/update/{id}','RosterAssignmentController@update');
                Route::delete('roster-assignment/delete/{id}','RosterAssignmentController@destroy');


                //RosterAssignmentStudentAction
                Route::post('roster-assignment/{roster_assignment_id}/make/help-request','RosterAssignmentStudentActionController@requestForHelp')->name(configFromModule('panel.student_actions_routes_name.help_request',ApplicationModules::ROSTER_ASSIGNMENT_MODULE_NAME));
                Route::post('roster-assignment/{roster_assignment_id}/make/check-answer-request','RosterAssignmentStudentActionController@requestForCheckAnswer')->name(configFromModule('panel.student_actions_routes_name.check_answer',ApplicationModules::ROSTER_ASSIGNMENT_MODULE_NAME));
                Route::post('roster-assignment/{roster_assignment_id}/make/response-on/student/{student_id}/request','RosterAssignmentStudentActionController@responseForStudentRequest');



                //RosterAssignmentPage
                Route::get('roster-assignment/{roster_assignment_id}/page/{page_id}','RosterAssignmentPageController@show');
                Route::get('roster-assignment/{roster_assignment_id}/student/{student_id}/page/{page_id}/for-parent','RosterAssignmentPageController@showForParent');
                Route::get('roster-assignment/{roster_assignment_id}/pages/with-student-pages','RosterAssignmentPageController@getByRosterAssignmentIdWithStudentPages');
                Route::post('roster-assignment/{roster_assignment_id}/page/{page_id}/lock','RosterAssignmentPageController@lockOrUnLock');
                Route::post('roster-assignment/{roster_assignment_id}/page/{page_id}/hide','RosterAssignmentPageController@hideAndUnHide');

                //StudentPage
                Route::post('roster-assignment/{roster_assignment_id}/page/{page_id}/student-pages/{action}','RosterAssignmentStudentPageController@actionByPageForDefinedRosterStudents');

                //RosterStudentAssignmentAttendance
                Route::get('roster-assignment/{roster_assignment_id}/students/attendance','RosterAssignmentStudentAttendanceController@getRosterAssignmentAttendance');
                Route::get('roster-assignment/{roster_assignment_id}/students/attendance/download','RosterAssignmentStudentAttendanceController@downloadRosterAssignmentAttendance');

                Route::get('roster/{roster_id}/students/attendance','RosterAssignmentStudentAttendanceController@getRosterAttendance');
                Route::get('roster/{roster_id}/students/attendance/download','RosterAssignmentStudentAttendanceController@downloadRosterAttendance');

                Route::get('student/{student_id}/attendance','RosterAssignmentStudentAttendanceController@getStudentAttendance');
                Route::get('student/{student_id}/attendance/download','RosterAssignmentStudentAttendanceController@downloadStudentAttendance');

                Route::post('roster-assignment/{roster_assignment_id}/student/{student_id}/mark-as-present','RosterAssignmentStudentAttendanceController@markStudentAsPresent');
                Route::post('roster-assignment/{roster_assignment_id}/mark-me-as-present','RosterAssignmentStudentAttendanceController@markMeAsPresent');
                Route::post('roster-assignment/{roster_assignment_id}/student/{student_id}/attendance/update','RosterAssignmentStudentAttendanceController@update');



//                Route::get('mark/test','RosterAssignmentStudentMarkController@getStudentsMarksByRosterAssignmentId');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin')], function () {

        });


    });


});




