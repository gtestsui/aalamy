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

Route::group(['namespace'=>'\Modules\Roster\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Roster
                Route::get('roster/my/all','RosterController@myRosters');
                Route::get('roster/my/by-level-subject/all','RosterController@myRostersByLevelSubject');
                Route::get('roster/my/all/doesnt-linked-to/assignment/{assignment_id}','RosterController@myRostersDoesntLinkedToDefinedAssignment');
                Route::get('roster/{id}/with-roster-assignments','RosterController@myRosterByIdWithRosterAssignment');
                Route::get('roster/my/all-QuestionTypes','RosterController@myRostersGroupedByOwners');
                Route::get('class/{class_id}/roster/my/all','RosterController@myRostersByClassId');
                Route::post('roster/create','RosterController@store');
                Route::post('roster/create/for-educator','RosterController@storeForEducator');
                Route::post('roster/update/{id}','RosterController@update');
                Route::post('roster/{id}/close-or-unclose','RosterController@closeOrUnClose');
                Route::delete('roster/delete/{id}','RosterController@softDelete');
                Route::delete('roster/force-delete/{id}','RosterController@destroy');


                //StudentRoster
                Route::get('roster/{roster_id}/student/all','RosterStudentController@getRosterStudentsByRosterId');
//                Route::post('roster/enroll/{code}','RosterStudentController@enrollToRosterByLink');
                Route::post('roster/{roster_id}/roster-student/add-to-roster','RosterStudentController@addStudentsToRoster');
                Route::post('roster/{roster_id}/roster-student/add-to-roster/for-educator','RosterStudentController@addStudentsToRosterForEducator');
                Route::delete('roster/{roster_id}/roster-student/delete/{id}','RosterStudentController@destroy');


                //RosterInvitation
                Route::post('roster/enroll/{code}','RosterInvitationController@enrollToRosterByCode');

            });

        });

        Route::group(['namespace'=>'\Modules\Roster\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Roster
            Route::get('class/{class_id}/roster/paginate','Super_RosterController@byClassId');
            Route::get('roster/paginate/{soft_delete?}','Super_RosterController@paginate');
            Route::get('roster/by-id/{id}/{soft_delete}','Super_RosterController@getElementByIdEvenItsDeleted');
            Route::get('educator/{educator_id}/rosters/all/{soft_delete?}','Super_RosterController@getEducatorRosters');
            Route::get('school/{school_id}/rosters/all/{soft_delete?}','Super_RosterController@getSchoolRosters');
            Route::delete('roster/{roster_id}/delete-or-restore','Super_RosterController@softDeleteOrRestore');
            Route::delete('roster/{roster_id}/force-delete','Super_RosterController@destroy');


        });


    });


});




