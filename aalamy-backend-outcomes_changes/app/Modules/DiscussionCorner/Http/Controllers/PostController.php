<?php

namespace Modules\DiscussionCorner\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByAccountType\DiscussionCornerByAccountTypeManagementFactory;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner\DiscussionCornerByOwnerManagementFactory;
use Modules\DiscussionCorner\Http\Controllers\Classes\PostMediaClass;
use Modules\DiscussionCorner\Http\DTO\PostData;
use Modules\DiscussionCorner\Http\Requests\Post\ApprovePostRequest;
use Modules\DiscussionCorner\Http\Requests\Post\DestroyPostRequest;
use Modules\DiscussionCorner\Http\Requests\Post\GetPostsByOwnerIdRequest;
use Modules\DiscussionCorner\Http\Requests\Post\GetPostsWaitingApproveRequest;
use Modules\DiscussionCorner\Http\Requests\Post\StorePostRequest;
use Modules\DiscussionCorner\Http\Requests\Post\UpdatePostRequest;
use Modules\DiscussionCorner\Http\Resources\PostResource;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerPostFile;
use Modules\Notification\Jobs\DiscussionCorner\SendNewPostWaitingApproveNotification;
use Modules\Notification\Jobs\DiscussionCorner\SendPostApprovalNotification;

class PostController extends Controller
{


    public function getRandomPaginate(Request $request){
        $user = $request->user();
        $userAccountObject = $user->{ucfirst($user->account_type)};
//        $discussionClassByAccountType = DiscussionCornerServices::createManageDiscussionClassByAccountType($user->account_type,$user);
        $discussionClassByAccountType = DiscussionCornerByAccountTypeManagementFactory::create($user);
        $posts = $discussionClassByAccountType->getRandomPostsPaginate();
        return ApiResponseClass::successResponse(
            PostResource::CustomCollection($posts,$user,$userAccountObject)
        );
    }


    public function show(Request $request,$post_id){
        $user = $request->user();
        $userAccountObject = $user->{ucfirst($user->account_type)};
        $discussionClassByAccountType = DiscussionCornerByAccountTypeManagementFactory::create($user);
        $post = $discussionClassByAccountType->getPostIHaveAccessToSeeByIdOrFail($post_id);
        $post->load([
            'School','Educator.User','User','Pictures','Files'/*,'Replies'*/,'Videos'
        ]);
        return ApiResponseClass::successResponse(
            new PostResource($post,$user,$userAccountObject)
        );
    }

    public function getWaitingApprove(GetPostsWaitingApproveRequest $request){
        $user = $request->user();
        $userAccountObject = $user->{ucfirst($user->account_type)};
//        $discussionClass = DiscussionCornerServices::createManageDiscussionClassByCornerOwner(ucfirst($user->account_type),$user->{ucfirst($user->account_type)});
        $discussionClass = DiscussionCornerByOwnerManagementFactory::create($user->account_type,$user->{ucfirst($user->account_type)});
        $posts = $discussionClass->getPostsWaitingApprovePaginate();
        return ApiResponseClass::successResponse(
            PostResource::CustomCollection($posts,$user,$userAccountObject)
        );

    }

    public function getByOwnerId(GetPostsByOwnerIdRequest $request){
        $user = $request->user();
        $userAccountObject = $user->{ucfirst($user->account_type)};
        $discussionClass = $request->getDiscussionClass();
        $posts = $discussionClass->getPostsPaginate();
        return ApiResponseClass::successResponse(
            PostResource::CustomCollection($posts,$user,$userAccountObject)
        );
    }

    public function store(StorePostRequest $request){
        $user = $request->user();
        $userAccountObject = $user->{ucfirst($user->account_type)};
        DB::beginTransaction();
        $postData = PostData::fromRequest($request);
        $post = DiscussionCornerPost::create($postData->all());
        $postMediaClass = new PostMediaClass($post);
        $postMediaClass->addMoreThanPictureToPost($postData->pictures);
        $postMediaClass->addMoreThanVideoToPost($postData->videos);
        $postMediaClass->addMoreThanFileToPost($postData->post_files);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewPostWaitingApproveNotification($post,$user));
        return ApiResponseClass::successResponse(
            new PostResource($post,$user,$userAccountObject)
        );
    }

    public function update(UpdatePostRequest $request){
        $user = $request->user();
        $userAccountObject = $user->{ucfirst($user->account_type)};
        $post = $request->getPost();
        DB::beginTransaction();
        $postData = PostData::fromRequest($request,true);
        $post->update($postData->initializeForUpdate($postData));
        $postMediaClass = new PostMediaClass($post);
        $postMediaClass->deletePictures($postData->deleted_picture_ids);
        $postMediaClass->addMoreThanPictureToPost($postData->pictures);
        $postMediaClass->deleteVideos($postData->deleted_video_ids);
        $postMediaClass->addMoreThanVideoToPost($postData->videos);
        $postMediaClass->deleteFiles($postData->deleted_file_ids);
        $postMediaClass->addMoreThanFileToPost($postData->post_files);
        DB::commit();
        ServicesClass::dispatchJob(new SendNewPostWaitingApproveNotification($post,$user));

        return ApiResponseClass::successResponse(
            new PostResource($post,$user,$userAccountObject)
        );


    }

    public function softDelete(DestroyPostRequest $request, $id){
        DB::beginTransaction();
        $post = $request->getPost();
        $post->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyPostRequest $request, $id){
        $post = $request->getPost();
        DiscussionCornerServices::deletePost($post);
        return ApiResponseClass::deletedResponse();
    }

    public function approve(ApprovePostRequest $request,$id){
        $user = $request->user();
        $post = $request->getPost();
        $post->approve();
        $post->load([
            'School','Educator.User','User','Pictures','Files'/*,'Replies'*/,'Videos'
        ]);
        ServicesClass::dispatchJob(new SendPostApprovalNotification($post,$user));
        return ApiResponseClass::successMsgResponse();
    }

    public function downloadPostFile($file_id){
        $file = DiscussionCornerPostFile::findOrFail($file_id);
        $originalExtension = FileManagmentServicesClass::getExtensionFileFromName($file->getRawOriginal('file'));
//        $originalExtension = ServicesClass::getExtensionFileFromName($file->getRawOriginal('file'));
        $newFileName = time().'.'.$originalExtension;

        return response()->download(
//            config('panel.default_disk_path').$file->getRawOriginal('file'), $newFileName
//            FileSystemServicesClass::getDiskBaseRoot().$file->getRawOriginal('file'), $newFileName
            FileManagmentServicesClass::resolveLinkFromDataToDownload($file,'file'), $newFileName

        );
    }

}
