<?php

namespace Modules\EducatorStudentRequest\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\EducatorRosterStudentRequest\SendEducatorRosterStudentRequestNotification;
use Modules\EducatorStudentRequest\Http\Controllers\Classes\EducatorStudentRequestServices;
use Modules\EducatorStudentRequest\Http\Requests\EducatorRosterStudentRequest\ApproveOrRejectEducatorRosterStudentRequest;
use Modules\EducatorStudentRequest\Http\Requests\EducatorRosterStudentRequest\GetMyEducatorRosterStudentRequest;
use Modules\EducatorStudentRequest\Http\Requests\EducatorRosterStudentRequest\SendEducatorRosterStudentRequest;
use Modules\EducatorStudentRequest\Http\Resources\EducatorRosterStudentRequestResource;
use Modules\EducatorStudentRequest\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\Roster;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\User\Models\Educator;

class EducatorRosterStudentRequestController extends Controller
{

    public function getMyRequests(GetMyEducatorRosterStudentRequest $request,$requestType){
        $user = $request->user();
        $myRequestsQuery = EducatorRosterStudentRequest::query()
            ->belongsTo($user)
            ->byType($requestType,$user->account_type);//received or sent request

        $myRequests = (clone $myRequestsQuery)->byStatus($request->status)
            ->with(['Educator.User','Student.User'])
            ->paginate(config('EducatorStudentRequest.panel.educator_requests_paginate_num'));
        $responseData = [
            'requests' => EducatorRosterStudentRequestResource::collection($myRequests),
            'requests_count' => EducatorStudentRequestServices::getRequestsCount(
                $myRequestsQuery,$request->page
            )
        ];
        return ApiResponseClass::successResponse($responseData);
    }

    public function sendRequestToStudents(SendEducatorRosterStudentRequest $request,$roster_id){
        $user = $request->user();
        $user->load('Educator');
        $roster = $request->getRoster();
        DB::beginTransaction();
        foreach ($request->student_ids as $studentId){
            EducatorRosterStudentRequest::create([
                'educator_id' => $user->Educator->id,
                'student_id' => $studentId,
                'roster_id' => $roster_id,
                'introductory_message' => $request->introductory_message,
            ]);
        }
        ServicesClass::dispatchJob(new SendEducatorRosterStudentRequestNotification($request->student_ids,$user,$roster,$request->introductory_message));
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }


    public function approveOrReject(ApproveOrRejectEducatorRosterStudentRequest $request,$educator_request_roster_id){
        $educatorRosterStudentRequest = $request->getEducatorRosterStudentRequest();
        DB::beginTransaction();
        $methodName = EducatorStudentRequestServices::getMethodNameFromRequestStatus($request->status);
        EducatorStudentRequestServices::{$methodName}(
            $educatorRosterStudentRequest,$request->reject_cause
        );

        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }


//    public function reject(ApproveOrRejectEducatorRosterStudentRequest $request,$educator_request_roster_id){
//        $educatorRosterStudentRequest = $request->getEducatorRosterStudentRequest();
//        EducatorStudentRequestServices::reject($educatorRosterStudentRequest,$request->reject_cause);
//        return ApiResponseClass::successMsgResponse();
//    }

}
