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

Route::group(['namespace'=>'\Modules\EducatorStudentRequest\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


                //EducatorRosterStudentRequest
                Route::get('educator/student-request/{requestType}/paginate','EducatorRosterStudentRequestController@getMyRequests');
                Route::post('roster/{roster_id}/roster-student/request/send','EducatorRosterStudentRequestController@sendRequestToStudents');
                Route::post('roster/educator-student-request/approve-or-reject/{educator_request_roster_id}','EducatorRosterStudentRequestController@approveOrReject');
                Route::post('roster-student-request/reject/{educator_request_roster_id}','EducatorRosterStudentRequestController@reject');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\EducatorStudentRequest\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function ()
        {

            //EducatorRosterStudentRequest
            Route::get('educator/student-request/paginate/{soft_delete?}','Super_EducatorRosterStudentRequestController@paginate');
            Route::delete('educator/student-request/{educator_request_id}/delete-or-restore','Super_EducatorRosterStudentRequestController@softDeleteOrRestore');
            Route::delete('educator/student-request/{educator_request_id}/force-delete','Super_EducatorRosterStudentRequestController@destroy');




        });


    });


});




