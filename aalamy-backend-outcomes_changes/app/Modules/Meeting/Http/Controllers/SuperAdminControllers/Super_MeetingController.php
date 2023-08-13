<?php

namespace Modules\Meeting\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Meeting\Http\Resources\SuperAdminResources\SuperAdminMeetingResource;
use Modules\Meeting\Models\Meeting;

class Super_MeetingController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $meetings = Meeting::search($request->key,[],[
                'Teacher.User','School','Educator.User'
            ])
            ->trashed($soft_delete)
            ->withCount('TargetUsers'/*,function ($query){
                return $query->isPresent();
            }*/)
            ->withAllRelations()
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(SuperAdminMeetingResource::collection($meetings));
    }


    public function softDeleteOrRestore(Request $request,$meeting_id){
        DB::beginTransaction();
        $meeting = Meeting::withDeletedItems()
            ->findOrFail($meeting_id);
        $meeting->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new SuperAdminMeetingResource($meeting));

    }

    public function destroy(Request $request,$meeting_id){
        DB::beginTransaction();
        $meeting = DiscussionCornerPost::withDeletedItems()
            ->findOrFail($meeting_id);
        $meeting->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
