<?php

namespace Modules\SchoolInvitation\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\SchoolInvitation\Http\Resources\SchoolStudentRequestResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\SchoolRequest\SendSchoolRequestApprovalNotification;
use Modules\Notification\Jobs\SchoolRequest\SendSchoolStudentRequestApprovalNotification;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\Notification\Jobs\SchoolRequest\SendSchoolStudentRequestNotification;
use Modules\SchoolInvitation\Http\Requests\SchoolStudent\ApprovalSchoolStudentRequestRequest;
use Modules\SchoolInvitation\Http\Requests\SchoolStudent\DestroySchoolStudentRequestRequest;
use Modules\SchoolInvitation\Http\Requests\SchoolStudent\GetMyReceivedSchoolStudentRequestsRequest;
use Modules\SchoolInvitation\Http\Requests\SchoolStudent\SendSchoolStudentRequestRequest;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\SchoolInvitation\Http\DTO\SchoolStudentRequestData;
use Modules\User\Models\SchoolStudent;


class SchoolStudentRequestController extends Controller
{

    public function getMyRequests(GetMyReceivedSchoolStudentRequestsRequest $request,$requestType){
        $user = $request->user();
        $myRequestsQuery = SchoolStudentRequest::query()
            ->belongsTo($user)
            ->byType($requestType,$user->account_type);//received or sent request

        $myRequests = (clone $myRequestsQuery)->byStatus($request->status)
            ->with(['School','Student.User'])
            ->paginate(config('SchoolInvitation.panel.requests_paginate_num'));

        $responseData = [
            'requests' => SchoolStudentRequestResource::collection($myRequests),
            'requests_count' => SchoolRequestServices::getRequestsCount(
                $myRequestsQuery,$request->page
            )
        ];

        return ApiResponseClass::successResponse($responseData);
    }

    public function send(SendSchoolStudentRequestRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $requestData = SchoolStudentRequestData::fromRequest($request,$user);

        SchoolRequestServices::checkFoundSchoolStudentRequest($requestData);
        SchoolRequestServices::checkStudentBelongsToSchool($requestData->student_id,$requestData->school_id);

        $studentRequest = SchoolStudentRequest::create($requestData->all());
        $studentRequest->load(['School.User','Student.User']);
        dispatchJob(new SendSchoolStudentRequestNotification($studentRequest));
        DB::commit();

        return ApiResponseClass::successMsgResponse();
    }

    /**
     * there is an observer work after approved to add the student to school
     *
     */
    public function approveOrReject(ApprovalSchoolStudentRequestRequest $request,$requestId){
        $user = $request->user();
        DB::beginTransaction();
        $studentRequest = $request->getSchoolStudentRequest();

        $studentRequest->update([
            'status' => config('SchoolInvitation.panel.student_request_statuses.'.$request->status),
            'reject_cause' => $request->status ==
                config('SchoolInvitation.panel.student_request_statuses.rejected')
                    ?$request->reject_cause
                    :null
        ]);
        if($request->status==config('SchoolInvitation.panel.student_request_statuses.approved')){
            SchoolStudent::create([
                'student_id' => $studentRequest->student_id,
                'school_id' => $studentRequest->school_id,
                'start_date' => Carbon::now(),
            ]);
        }

        dispatchJob(new SendSchoolStudentRequestApprovalNotification($studentRequest,config('SchoolInvitation.panel.student_request_statuses.approved')));
        DB::commit();
        return ApiResponseClass::successMsgResponse();

    }

    public function destroy(DestroySchoolStudentRequestRequest $request,$requestId){
        $user = $request->user();
        DB::beginTransaction();
        $studentRequest = $request->getSchoolStudentRequest();
        $studentRequest->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

   /* public function reject(RejectSchoolStudentRequestRequest $request,$requestId){
        $user = $request->user();
        $studentRequest = $request->getSchoolStudentRequest();

        $studentRequest->update([
            'status' => config('SchoolInvitation.panel.student_request_statuses.rejected'),
            'reject_cause' => $request->reject_cause
        ]);
        dispatchJob(new SendSchoolRequestApprovalNotification($studentRequest,config('SchoolInvitation.panel.student_request_statuses.rejected')));

        return ApiResponseClass::successMsgResponse();
    }*/



}
