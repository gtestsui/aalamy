<?php

namespace Modules\SchoolInvitation\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\SchoolInvitation\Http\Resources\SchoolTeacherRequestResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;

class Super_SchoolTeacherRequestController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $schoolTeachersRequests = SchoolTeacherRequest::search($request->key,[],[
                'Educator.User','School'
            ])
            ->trashed($soft_delete)
            ->with(['Educator.User','School'])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(SchoolTeacherRequestResource::collection($schoolTeachersRequests));
    }


    public function softDeleteOrRestore(Request $request,$school_teacher_request_id){
        DB::beginTransaction();
        $schoolTeacherRequest = SchoolTeacherRequest::withDeletedItems()
            ->findOrFail($school_teacher_request_id);
        $schoolTeacherRequest->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new SchoolTeacherRequestResource($schoolTeacherRequest));

    }

    public function destroy(Request $request,$school_teacher_request_id){
        DB::beginTransaction();
        $schoolTeacherRequest = SchoolTeacherRequest::withDeletedItems()
            ->findOrFail($school_teacher_request_id);
        $schoolTeacherRequest->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
