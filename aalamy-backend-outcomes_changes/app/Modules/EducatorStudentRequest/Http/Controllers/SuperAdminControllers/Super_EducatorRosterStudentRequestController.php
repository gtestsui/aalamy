<?php

namespace Modules\EducatorStudentRequest\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

class Super_EducatorRosterStudentRequestController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $educatorRequests = EducatorRosterStudentRequest::search($request->key,[],[
                'Student.User','Educator.User'
            ])
            ->trashed($soft_delete)
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(EducatorRosterStudentRequestResource::collection($educatorRequests));
    }


    public function softDeleteOrRestore(Request $request,$educator_request_id){
        DB::beginTransaction();
        $educatorRequest = EducatorRosterStudentRequest::withDeletedItems()
            ->findOrFail($educator_request_id);
        $educatorRequest->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new EducatorRosterStudentRequestResource($educatorRequest));

    }

    public function destroy(Request $request,$educator_request_id){
        DB::beginTransaction();
        $educatorRequest = EducatorRosterStudentRequest::withDeletedItems()
            ->findOrFail($educator_request_id);
        $educatorRequest->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }
}
