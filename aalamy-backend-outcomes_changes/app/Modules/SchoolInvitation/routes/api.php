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

Route::group(['namespace'=>'\Modules\SchoolInvitation\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {
                //Invitations
                Route::post('school/invitation/send','SchoolTeacherInvitationController@send');

                //TeacherRequest
                Route::get('school/teacher-request/{requestType}/paginate','SchoolTeacherRequestController@getMyRequests');
                Route::post('school/teacher-request/send','SchoolTeacherRequestController@send');
                Route::post('school/teacher-request/{requestId}/approve-or-reject','SchoolTeacherRequestController@approveOrReject');
                Route::delete('school/teacher-request/{requestId}/force-delete','SchoolTeacherRequestController@destroy');
//                Route::post('school/teacher-request/{requestId}/reject','SchoolTeacherRequestController@reject');


                //StudentRequest
                Route::get('school/student-request/{requestType}/paginate','SchoolStudentRequestController@getMyRequests');
                Route::post('school/student-request/send','SchoolStudentRequestController@send');
                Route::post('school/student-request/{requestId}/approve-or-reject','SchoolStudentRequestController@approveOrReject');
                Route::delete('school/student-request/{requestId}/force-delete','SchoolStudentRequestController@destroy');
//                Route::post('school/student-request/{requestId}/reject','SchoolStudentRequestController@reject');

            });

        });



        Route::group(['namespace'=>'\Modules\SchoolInvitation\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //SchoolStudentRequest
            Route::get('school/student-request/paginate/{soft_delete?}','Super_SchoolStudentRequestController@paginate');
            Route::delete('school/student-request/{school_student_request_id}/delete-or-restore','Super_SchoolStudentRequestController@softDeleteOrRestore');
            Route::delete('school/student-request/{school_student_request_id}/force-delete','Super_SchoolStudentRequestController@destroy');

            //SchoolTeacherRequest
            Route::get('school/teacher-request/paginate/{soft_delete?}','Super_SchoolTeacherRequestController@paginate');
            Route::delete('school/teacher-request/{school_teacher_request_id}/delete-or-restore','Super_SchoolTeacherRequestController@softDeleteOrRestore');
            Route::delete('school/teacher-request/{school_teacher_request_id}/force-delete','Super_SchoolTeacherRequestController@destroy');

            //SchoolTeacherInvitation
            Route::get('school/teacher-invitation/paginate','Super_SchoolTeacherInvitationController@paginate');

        });




    });


});




