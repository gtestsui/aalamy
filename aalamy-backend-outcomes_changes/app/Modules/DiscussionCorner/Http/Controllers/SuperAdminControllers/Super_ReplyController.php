<?php

namespace Modules\DiscussionCorner\Http\Controllers\SuperAdminControllers;


use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Http\Resources\ReplyResource;
use Modules\DiscussionCorner\Models\DiscussionCornerPostReply;

class Super_ReplyController extends Controller
{


    public function getByPostIdPaginate(Request $request,$post_id,$soft_delete=null){
        $replies = DiscussionCornerPostReply::search($request->key,[],[
                'User'
            ])
            ->trashed($soft_delete)
            ->where('post_id',$post_id)
            ->with('User')
            ->paginate(config('DiscussionCorner.panel.reply_count_per_page'));
        return ApiResponseClass::successResponse(ReplyResource::collection($replies));
    }



    public function softDeleteOrRestore(Request $request,$reply_id){
        DB::beginTransaction();
        $reply = DiscussionCornerPostReply::withDeletedItems()
            ->findOrFail($reply_id);
        $reply->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new ReplyResource($reply));

    }

    public function destroy(Request $request,$reply_id){
        DB::beginTransaction();
        $reply = DiscussionCornerPostReply::withDeletedItems()
            ->findOrFail($reply_id);
        $reply->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }


}
