<?php

namespace Modules\SchoolInvitation\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\SchoolInvitation\Http\Resources\SchoolTeacherRequestResource;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\SchoolRequest\SendSchoolTeacherRequestApprovalNotification;
use Modules\SchoolInvitation\Http\Controllers\Classes\SchoolRequestServices;
use Modules\Notification\Jobs\SchoolRequest\SendSchoolTeacherRequestNotification;
use Modules\SchoolInvitation\Http\Requests\SchoolTeacher\ApprovalSchoolTeacherRequestRequest;
use Modules\SchoolInvitation\Http\Requests\SchoolTeacher\DestroySchoolTeacherRequestRequest;
use Modules\SchoolInvitation\Http\Requests\SchoolTeacher\GetMyReceivedSchoolTeacherRequestsRequest;
use Modules\SchoolInvitation\Http\Requests\SchoolTeacher\SendSchoolTeacherRequestRequest;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\SchoolInvitation\Http\DTO\SchoolTeacherRequestData;


class SchoolTeacherRequestController extends Controller
{

    public function getMyRequests(GetMyReceivedSchoolTeacherRequestsRequest $request,$requestType){
        $user = $request->user();
        $myRequestsQuery = SchoolTeacherRequest::query()
            ->belongsTo($user)
            ->byType($requestType,$user->account_type);//received or sent request

        $myRequests = (clone $myRequestsQuery)->byStatus($request->status)
            ->with(['School','Educator.User'])
            ->paginate(10);
        $responseData = [
          'requests' => SchoolTeacherRequestResource::collection($myRequests),
          'requests_count' => SchoolRequestServices::getRequestsCount(
              $myRequestsQuery,$request->page
          )
        ];
        return ApiResponseClass::successResponse($responseData);
    }


    public function send(SendSchoolTeacherRequestRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $requestData = SchoolTeacherRequestData::fromRequest($request,$user);

        SchoolRequestServices::checkFoundSchoolTeacherRequest($requestData);
        SchoolRequestServices::checkTeacherBelongsToSchool($requestData->educator_id,$requestData->school_id);

        $teacherRequest = SchoolTeacherRequest::create($requestData->all());
        $teacherRequest->load(['School.User','Educator.User']);
        dispatchJob(new SendSchoolTeacherRequestNotification($teacherRequest));
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }

    /**
     * there is an observer work after approved to add the teacher to school
     *
     */
    public function approveOrReject(ApprovalSchoolTeacherRequestRequest $request,$requestId){
        $user = $request->user();
        DB::beginTransaction();
        $teacherRequest = $request->getSchoolTeacherRequest();

        $teacherRequest->update([
           'status' => config('SchoolInvitation.panel.teacher_request_statuses.'.$request->status),
           'reject_cause' => $request->status ==
                config('SchoolInvitation.panel.teacher_request_statuses.rejected')
                ?$request->reject_cause
                :null

        ]);
        dispatchJob(new SendSchoolTeacherRequestApprovalNotification($teacherRequest,config('SchoolInvitation.panel.teacher_request_statuses.approved')));

        DB::commit();
        return ApiResponseClass::successMsgResponse();

    }

    public function destroy(DestroySchoolTeacherRequestRequest $request,$requestId){
        $user = $request->user();
        DB::beginTransaction();
        $studentRequest = $request->getSchoolTeacherRequest();
        $studentRequest->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

    /*public function reject(RejectSchoolTeacherRequestRequest $request,$requestId){
        $user = $request->user();
        $teacherRequest = $request->getSchoolTeacherRequest();

        $teacherRequest->update([
            'status' => config('SchoolInvitation.panel.teacher_request_statuses.rejected'),
            'reject_cause' => $request->reject_cause
        ]);
        dispatchJob(new SendSchoolRequestApprovalNotification($teacherRequest,config('SchoolInvitation.panel.teacher_request_statuses.rejected')));

        return ApiResponseClass::successMsgResponse();
    }*/


}
