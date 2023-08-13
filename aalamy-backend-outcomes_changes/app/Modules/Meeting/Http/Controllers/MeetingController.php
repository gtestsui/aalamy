<?php

namespace Modules\Meeting\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use App\Modules\Meeting\Http\Requests\GetMyMeetingRecordRequest;
use App\Modules\Meeting\Http\Requests\GetMyOwnMeetingAttendanceCalendarRequest;
use App\Modules\Meeting\Http\Requests\GetMyOwnMeetingsRequest;
use App\Modules\Meeting\Http\Requests\GetRunningMeetingsTargetMeRequest;
use App\Modules\Meeting\Http\Requests\JoinRequest;
use App\Modules\Meeting\Http\Requests\StoreAndStartMeetingRequest;
use App\Modules\Meeting\Http\Requests\EndMeetingRequest;
use App\Modules\Meeting\Http\Requests\GetMyOwnRunningMeetingsRequest;
use App\Modules\Meeting\Http\Requests\JoinAsAttendeeRequest;
use App\Modules\Meeting\Http\Requests\JoinAsModeratorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Meeting\Http\Controllers\BBB\MeetBBB;
use Modules\Meeting\Http\Controllers\BBB\MeetRecodeBBB;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Meeting\MeetingManagementFactory;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\MeetingOwnerManagementFactory;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers\MeetingTargetManagementFactory;
use Modules\Meeting\Http\Controllers\Classes\MeetingServices;
use Modules\Meeting\Http\Controllers\Classes\RunningMeetingClass;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Http\Resources\MeetingResource;
use Modules\Meeting\Models\Meeting;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\Notification\Jobs\Meeting\SendNewMeetingNotification;
use Modules\User\Http\Controllers\Classes\UserServices;

class MeetingController extends Controller
{

    /**
     * get all or just belongs to class_id
     * @param GetMyOwnMeetingsRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ErrorMsgException
     */
    public function getMyOwnMeetingsPaginate(GetMyOwnMeetingsRequest $request){
        $user = $request->user();
        $meetingClass = MeetingOwnerManagementFactory::create($user,$request->my_teacher_id);
        $myMeetings = $meetingClass->getMyOwnMeetingsPaginate($request->class_id,$request->search_key);
        return ApiResponseClass::successResponse(MeetingResource::collection($myMeetings));

    }



    public function getRecordByMeeet(GetMyMeetingRecordRequest $request,$meeting_id){
        $user = $request->user();
        $recordedMeet = MeetRecodeBBB::getRecordingByMeetingId($meeting_id);
        if(!count($recordedMeet)){
            throw new ErrorMsgException("This meet didn't recorded!");
        }
        return ApiResponseClass::successResponse($recordedMeet);
    }


//    public function getMeetingAttendanceCalendar(GetMyOwnMeetingAttendanceCalendarRequest $request){
//        $user = $request->user();
//        $meeting = $request->getMeeting();
//        $meeting->load(['TargetUsers'=>function($query){
//            return $query->withAllRelations();
//        }]);
//        return ApiResponseClass::successResponse(new MeetingResource($meeting));
//    }


    public function getMyRunningMeetings(Request $request){
        $user = $request->user();
        MeetBBB::setModeratorPassword('password');
        $meetingClass = MeetingManagementFactory::create($user,$request->my_teacher_id);
        $myRunningMeetings = $meetingClass->myRunningMeetingsTargetMeOrImTheOwner();


        return ApiResponseClass::successResponse(
            MeetingResource::collection($myRunningMeetings)
        );
    }


    public function getMyOwnRunningMeetings(GetMyOwnRunningMeetingsRequest $request){
        $user = $request->user();
        $meetingClass = MeetingOwnerManagementFactory::create($user,$request->my_teacher_id);
        $myRunningMeetings = $meetingClass->myOwnRunningMeetings();

        return ApiResponseClass::successResponse(MeetingResource::collection($myRunningMeetings));
    }

    public function getMyRunningMeetingsTargetMe(GetRunningMeetingsTargetMeRequest $request){
        $user = $request->user();
        $meetingClass = MeetingTargetManagementFactory::create($user,$request->my_teacher_id);
        $runningMeetingsTargetMe = $meetingClass->myRunningMeetingsTargetMe();


        return ApiResponseClass::successResponse(MeetingResource::collection($runningMeetingsTargetMe));
    }

    public function storeAndStartMeeting(StoreAndStartMeetingRequest $request){
		// return response()->json('si');
        set_time_limit(0);
        $user = $request->user();
        DB::beginTransaction();
        $meetingData = MeetingData::fromRequest($request);
        $meeting = Meeting::create($meetingData->all());

        $meetingClass = MeetingOwnerManagementFactory::create($user,$request->my_teacher_id);
        $arrayForCreate = $meetingClass->prepareMeetingTargetUserArray($meetingData,$meeting);
        MeetingTargetUser::insert($arrayForCreate);


        MeetBBB::setModeratorPassword($meetingData->moderator_password);
        MeetBBB::setAttendeePassword($meetingData->attendee_password);
        MeetBBB::setDuration(
            MeetingServices::getAvilableDurationFromMyPlan($user,$request->my_teacher_id)
        );
        $meetingUrl = MeetBBB::createAndStart(
            $meeting->id,
            $meetingData->title,
            $meetingData->max_participants,
            $user->fname.' '.$user->lname,
            $user->id
        );
        //here we should send notification to the target users
        ServicesClass::dispatchJob(new SendNewMeetingNotification($arrayForCreate,$meeting));

        DB::commit();
        return ApiResponseClass::successResponse([
            'meeting_url'=>$meetingUrl,
            'meeting' => new MeetingResource($meeting)
            ]);
    }


    public function join(JoinRequest $request,$meeting_id){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);
        $meeting = Meeting::findOrFail($meeting_id);
        if($meeting->imTheModerator($accountType,$accountObject)) {
            $meetingUrl = MeetingServices::joinAsModerator($meeting, $user);
            return ApiResponseClass::successResponse(['meeting_url' => $meetingUrl]);
        }
        $meetingTargetUser = $meeting->imFromAttendees($accountType,$accountObject);
        if($meetingTargetUser){
            $meetingUrl = MeetingServices::joinAsAttende($meeting, $user);

            $meetingTargetUser->makeAsPresent();
            return ApiResponseClass::successResponse(['meeting_url'=>$meetingUrl]);
        }
        throw new ErrorUnAuthorizationException();

    }

    public function joinAsModerator(JoinAsModeratorRequest $request,$meeting_id){
        $user = $request->user();
        $meeting = $request->getMeeting();
        MeetBBB::setModeratorPassword($meeting->moderator_password);
        $meetingUrl = MeetBBB::joinModerator(
            $meeting->id,
            $user->fname.' '.$user->lname,
            $user->id
        );
        return ApiResponseClass::successResponse(['meeting_url'=>$meetingUrl]);
    }

    public function joinAsAttendee(JoinAsAttendeeRequest $request,$meeting_id){
        $user = $request->user();
        $meeting = $request->getMeeting();
        MeetBBB::setAttendeePassword($meeting->attendee_password);
        $meetingUrl = MeetBBB::joinAttendee(
            $meeting->id,
            $user->fname.' '.$user->lname,
            $user->id
        );
        return ApiResponseClass::successResponse(['meeting_url'=>$meetingUrl]);
    }


    public function endMeeting(EndMeetingRequest $request,$meeting_id){
        $user = $request->user();
        $meeting = $request->getMeeting();
        MeetBBB::setModeratorPassword($meeting->moderator_password);
        MeetBBB::end($meeting_id);
        return ApiResponseClass::successMsgResponse();

    }




}
