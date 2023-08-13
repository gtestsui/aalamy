<?php

namespace Modules\Meeting\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Meeting\Http\Resources\SuperAdminResources\SuperAdminMeetingResource;
use Modules\Meeting\Http\Resources\SuperAdminResources\SuperAdminMeetingTargetUserResource;
use Modules\Meeting\Models\Meeting;
use Modules\Meeting\Models\MeetingTargetUser;

class Super_MeetingTargetedUserController extends Controller
{

    public function getByMeetingId(Request $request,$meeting_id,$soft_delete=null){
        $targetedUsers = MeetingTargetUser::search($request->key,[],[
                'Teacher.User','Parent.User','Student.User'
            ])
            ->where('meeting_id',$meeting_id)
            ->trashed($soft_delete)
            ->withAllRelations()
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(SuperAdminMeetingTargetUserResource::collection($targetedUsers));
    }


    public function softDeleteOrRestore(Request $request,$meeting_target_user_id){
        DB::beginTransaction();
        $targetedUser = MeetingTargetUser::withDeletedItems()
            ->findOrFail($meeting_target_user_id);
        $targetedUser->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new SuperAdminMeetingTargetUserResource($targetedUser));

    }

    public function destroy(Request $request,$meeting_target_user_id){
        DB::beginTransaction();
        $targetedUser = DiscussionCornerPost::withDeletedItems()
            ->findOrFail($meeting_target_user_id);
        $targetedUser->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
