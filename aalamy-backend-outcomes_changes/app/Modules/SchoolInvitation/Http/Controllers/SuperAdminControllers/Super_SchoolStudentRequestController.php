<?php

namespace Modules\SchoolInvitation\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\SchoolInvitation\Http\Resources\SchoolStudentRequestResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;

class Super_SchoolStudentRequestController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $schoolStudentRequests = SchoolStudentRequest::search($request->key,[],[
                'Student.User','School'
            ])
            ->trashed($soft_delete)
            ->with(['Student.User','School'])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(SchoolStudentRequestResource::collection($schoolStudentRequests));
    }


    public function softDeleteOrRestore(Request $request,$school_student_request_id){
        DB::beginTransaction();
        $schoolStudentRequest = SchoolStudentRequest::withDeletedItems()
            ->findOrFail($school_student_request_id);
        $schoolStudentRequest->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new SchoolStudentRequestResource($schoolStudentRequest));

    }

    public function destroy(Request $request,$school_student_request_id){
        DB::beginTransaction();
        $schoolStudentRequest = SchoolStudentRequest::withDeletedItems()
            ->findOrFail($school_student_request_id);
        $schoolStudentRequest->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
