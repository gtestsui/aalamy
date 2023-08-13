<?php

namespace Modules\DiscussionCorner\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\DiscussionCorner\Http\DTO\ReplyData;
use Modules\DiscussionCorner\Http\Requests\Reply\DestroyReplyRequest;
use Modules\DiscussionCorner\Http\Requests\Reply\DisplayReplyRequest;
use Modules\DiscussionCorner\Http\Requests\Reply\StoreReplyRequest;
use Modules\DiscussionCorner\Http\Requests\Reply\UpdateReplyRequest;
use Modules\DiscussionCorner\Http\Resources\ReplyResource;
use Modules\DiscussionCorner\Models\DiscussionCornerPostPicture;
use Modules\DiscussionCorner\Models\DiscussionCornerPostReply;

class ReplyController extends Controller
{


    public function getByPostIdPaginate(DisplayReplyRequest $request,$postId){
        $replies = DiscussionCornerPostReply::where('post_id',$postId)
            ->with('User')
            ->paginate(config('DiscussionCorner.panel.reply_count_per_page'));
        return ApiResponseClass::successResponse(ReplyResource::collection($replies));
    }


    public function store(StoreReplyRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $replyData = ReplyData::fromRequest($request);
        $reply = DiscussionCornerPostReply::create($replyData->all());
        $reply->load('User');
        DB::commit();
        return ApiResponseClass::successResponse(new ReplyResource($reply));
    }


    public function update(UpdateReplyRequest $request){
        $user = $request->user();
        $reply = $request->getReply();
        DB::beginTransaction();
        $replyData = ReplyData::fromRequest($request,true);
        $reply->update($replyData->initializeForUpdate($replyData));
        $reply->load('User');
        DB::commit();
        return ApiResponseClass::successResponse(new ReplyResource($reply));
    }

    public function destroy(DestroyReplyRequest $request, $id){
        $reply = $request->getReply();
        $reply->delete();
        return ApiResponseClass::deletedResponse();
    }


}
