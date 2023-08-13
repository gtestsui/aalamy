<?php

namespace Modules\Assignment\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Assignment\Http\Controllers\Classes\AssignmentFolderServices;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignmentFolder\AssignmentFolderManagementFactory;
use Modules\Assignment\Http\DTO\AssignmentFolderData;
use Modules\Assignment\Http\Requests\AssignmentFolder\StoreAssignmentFolderRequest;
use Modules\Assignment\Http\Requests\AssignmentFolder\DestroyAssignmentFolderRequest;
use Modules\Assignment\Http\Requests\AssignmentFolder\GetFolderContentRequest;
use Modules\Assignment\Http\Requests\AssignmentFolder\GetMyAssignmentFoldersRequest;
use Modules\Assignment\Http\Requests\AssignmentFolder\UpdateAssignmentFolderRequest;
use Modules\Assignment\Http\Resources\AssignmentFolderResource;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\AssignmentFolder;


class AssignmentFolderController extends Controller
{


    public function getMyRootAssignmentFolders(GetMyAssignmentFoldersRequest $request){
        $user = $request->user();
        $manageAssignmentFolderClass = AssignmentFolderManagementFactory::create($user);
        $assignmentFolders = $manageAssignmentFolderClass->myRootAssignmentFoldersPaginate();

        return ApiResponseClass::successResponse(AssignmentFolderResource::collection($assignmentFolders));
    }


    /**
     * return all my folders paginate
     * @param GetMyAssignmentFoldersRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ErrorMsgException
     */
    public function getMyAssignmentFolders(GetMyAssignmentFoldersRequest $request){
        $user = $request->user();
        $manageAssignmentFolderClass = AssignmentFolderManagementFactory::create($user);
        $assignmentFolders = $manageAssignmentFolderClass->myAssignmentFoldersPaginate();

        return ApiResponseClass::successResponse(AssignmentFolderResource::collection($assignmentFolders));
    }


    public function getFolderContent(GetFolderContentRequest $request,$id){
        $user = $request->user();
        $folders = null;
        $assignments = null;

        $upperFolders = AssignmentFolder::with('AllParents')->where('id',$id)->first();

        if(AssignmentFolderServices::clientIsNeedContentOfFolders($request->content_type)){
            $myFolders = AssignmentFolder::where('parent_id',$id)
                ->search($request->key)
                ->with('Parent')
                ->paginate(100);
            $folders = count($myFolders)?AssignmentFolderResource::collection($myFolders):$folders;
        }

        if(AssignmentFolderServices::clientIsNeedContentOfAssignments($request->content_type)){
            $myAssignments = Assignment::where('assignment_folder_id',$id)
                ->search($request->key)
                ->with([
                    'AssignmentFolder',
                    'LevelSubject.Level',
                    'LevelSubject.Subject',
                ])
                ->withCount('Pages')
                ->paginate(100);
            $assignments = count($myAssignments)?AssignmentResource::collection($myAssignments):$assignments;
        }
        return ApiResponseClass::successResponse([
            'upper_folders' =>  $upperFolders,
            'folders' =>  $folders,
            'assignments' =>  $assignments,
        ]);

    }




    public function store(StoreAssignmentFolderRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $assignmentFolderData = AssignmentFolderData::fromRequest($request);
        $assignmentFolder = AssignmentFolder::create($assignmentFolderData->all());
        DB::commit();
        return ApiResponseClass::successResponse(new AssignmentFolderResource($assignmentFolder));
    }

    public function update(UpdateAssignmentFolderRequest $request,$id){
        $user = $request->user();
        DB::beginTransaction();
        $assignmentFolder = $request->getAssignmentFolder();
        $assignmentFolderData = AssignmentFolderData::fromRequest($request);
        $assignmentFolder->update($assignmentFolderData->initializeForUpdate());
        DB::commit();
        return ApiResponseClass::successResponse(new AssignmentFolderResource($assignmentFolder));
    }


    public function softDelete(DestroyAssignmentFolderRequest $request,$id){
        DB::beginTransaction();
        $user = $request->user();
        $assignmentFolder = $request->getAssignmentFolder();
        $assignmentFolder->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

    public function destroy(DestroyAssignmentFolderRequest $request,$id){
        $user = $request->user();
        $assignmentFolder = $request->getAssignmentFolder();
        $assignmentFolder->delete();
        return ApiResponseClass::deletedResponse();
    }

}
