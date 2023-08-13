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
$module = ApplicationModules::MEETING_MODULE_NAME;

Route::group(['namespace'=>"\Modules\Meeting\Http\Controllers",'prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Meeting
                Route::get('meeting/running/my-and-target-me','MeetingController@getMyRunningMeetings');
                Route::get('meeting/running/my','MeetingController@getMyOwnRunningMeetings');
                Route::get('meeting/my/paginate','MeetingController@getMyOwnMeetingsPaginate');
                Route::get('meeting/{id}/record','MeetingController@getRecordByMeeet');
//                Route::get('meeting/{id}/attendee-calendar','MeetingController@getMeetingAttendanceCalendar');
                Route::get('meeting/running/is-target-me','MeetingController@getMyRunningMeetingsTargetMe');
                Route::post('meeting/create-and-start','MeetingController@storeAndStartMeeting');
                Route::post('meeting/{meeting_id}/join','MeetingController@join');
                Route::post('meeting/{meeting_id}/moderator/join','MeetingController@joinAsModerator');
                Route::post('meeting/{meeting_id}/attendee/join','MeetingController@joinAsAttendee');
                Route::post('meeting/{meeting_id}/end','MeetingController@endMeeting');

                //MeetingAttendee
                Route::post('meeting/meeting-target/{meeting_targeted_id}','MeetingAttendeeController@updateAttendeeState');
                Route::get('meeting/{id}/attendee-calendar','MeetingAttendeeController@getMeetingAttendance');
                Route::get('meeting/{id}/attendee-calendar/download','MeetingAttendeeController@downloadMeetingAttendance');


            });

        });

        Route::group(['namespace'=>'\Modules\Meeting\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Meeting
            Route::get('meeting/paginate/{soft_delete?}','Super_MeetingController@paginate');
            Route::delete('meeting/{meeting_id}/delete-or-restore','Super_MeetingController@softDeleteOrRestore');
            Route::delete('meeting/{meeting_id}/force-delete','Super_MeetingController@destroy');

            //MeetingTargetedUser
            Route::get('meeting/{meeting_id}/paginate/{soft_delete?}','Super_MeetingTargetedUserController@getByMeetingId');
            Route::delete('meeting/target-user/{meeting_target_user_id}/delete-or-restore','Super_MeetingTargetedUserController@softDeleteOrRestore');
            Route::delete('meeting/target-user/{meeting_target_user_id}/force-delete','Super_MeetingTargetedUserController@destroy');


        });


    });


});




