<?php

namespace Modules\DiscussionCorner\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Http\Resources\PostResource;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;

class Super_PostController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){

        $posts = DiscussionCornerPost::search($request->key,[],[
                'User','School','Educator.User'
            ])
            ->trashed($soft_delete)
            ->withCount('Replies')
            ->withAllRelations()
            ->with([
                'User.Parent',
                'User.Student',
                'User.School',
                'User.Educator',
            ])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(PostResource::collection($posts));
    }


    public function show(Request $request,$post_id){
        $post = DiscussionCornerPost::withDeletedItems()
            ->withCount('Replies')
            ->withAllRelations()
            ->findOrFail($post_id);
        return ApiResponseClass::successResponse(new PostResource($post));
    }


    public function softDeleteOrRestore(Request $request,$post_id){
        DB::beginTransaction();
        $post = DiscussionCornerPost::withDeletedItems()
            ->findOrFail($post_id);
        $post->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new PostResource($post));

    }

    public function destroy(Request $request,$post_id){
        DB::beginTransaction();
        $post = DiscussionCornerPost::withDeletedItems()
            ->findOrFail($post_id);
        $post->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
