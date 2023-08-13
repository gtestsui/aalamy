<?php

namespace Modules\Assignment\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\EducatorAssignment;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\SchoolAssignment;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Http\DTO\ClassData;
use Modules\ClassModule\Http\Requests\ClassRequest\DestroyClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\GetClassByLevelIdRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\ShowClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\StoreClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\UpdateClassRequest;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\ClassModule\Http\Requests\ClassRequest\GetMyClassesRequest;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class Super_AssignmentController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $assignments = Assignment::search($request->key,[],[
            'AssignmentFolder',
            'Teacher.User',
            'School',
            'Educator.User',
            'Unit',
            'Lesson',
            'LevelSubject'=>['Level','Subject'],
        ])
        ->trashed($soft_delete)
        ->withCount('Pages')
        ->with([
            'AssignmentFolder',
            'Teacher.User',
            'School',
            'Educator.User',
            'Unit',
            'Lesson',
            'LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            }])
        ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(AssignmentResource::collection($assignments));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $assignment = Assignment::withCount('Pages')
            ->with([
                'AssignmentFolder',
                'Teacher.User',
                'School',
                'Educator.User',
                'Unit',
                'Lesson',
                'LevelSubject'=>function($query){
                    return $query->with(['Level','Subject']);
                }])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new AssignmentResource($assignment));

    }

    public function getEducatorAssignments(Request $request,$educator_id,$soft_delete=null){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorClass = new EducatorAssignment($educator);
        $assignments = $educatorClass->myAssignmentsWithPagesCountForAdmin($soft_delete);
        return ApiResponseClass::successResponse(AssignmentResource::collection($assignments));

    }

    public function getSchoolAssignments(Request $request,$school_id,$soft_delete=null){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolClass = new SchoolAssignment($school);
        $assignments = $schoolClass->myAssignmentsWithPagesCountForAdmin($soft_delete);
        return ApiResponseClass::successResponse(AssignmentResource::collection($assignments));

    }


    public function softDeleteOrRestore(Request $request,$assignment_id){
        DB::beginTransaction();
        $assignment = Assignment::withDeletedItems()
            ->findOrFail($assignment_id);
        $assignment->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new AssignmentResource($assignment));

    }

    public function destroy(Request $request,$assignment_id){
        DB::beginTransaction();
        $assignment = Assignment::withDeletedItems()
            ->findOrFail($assignment_id);
        $assignment->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
