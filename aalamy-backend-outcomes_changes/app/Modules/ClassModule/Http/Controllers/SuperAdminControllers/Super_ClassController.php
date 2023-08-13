<?php

namespace Modules\ClassModule\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\SchoolClassManagement;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\ClassModule\Models\ClassModel;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class Super_ClassController extends Controller
{

    public function paginate(Request $request,$soft_delete=null){
        $classes = ClassModel::search($request->key,[],['Level'])
            ->trashed($soft_delete)
            ->with(['Level'=>function($query){
                return $query->withDeletedItems()
                    ->with(['User' => function($q){
                    return $q->withDeletedItems()
                        ->with(['Educator','School']);
                }]);
            }])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(ClassResource::collection($classes));
    }


    public function getEducatorClasses(Request $request,$educator_id,$soft_delete=null){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorClass = new EducatorClassManagement($educator);
        $classes = $educatorClass->myClassesQuery()
            ->trashed($soft_delete)
            ->search($request->key,[],['Level'])
            ->get();
        return ApiResponseClass::successResponse(ClassResource::collection($classes));

    }

    public function getSchoolClasses(Request $request,$school_id,$soft_delete=null){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolClass = new SchoolClassManagement($school);
        $classes = $schoolClass->myClassesQuery()
            ->trashed($soft_delete)
            ->search($request->key,[],['Level'])
            ->get();
        return ApiResponseClass::successResponse(ClassResource::collection($classes));

    }


    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $assignment = ClassModel::with(['Level'=>function($query){
                return $query->withDeletedItems()
                    ->with(['User' => function($q){
                        return $q->withDeletedItems()
                            ->with(['Educator','School']);
                    }]);
            }])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new ClassResource($assignment));

    }


    public function softDeleteOrRestore(Request $request,$class_id){
        DB::beginTransaction();
        $class = ClassModel::withDeletedItems()
            ->findOrFail($class_id);
        $class->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new ClassResource($class));

    }

    public function destroy(Request $request,$class_id){
        DB::beginTransaction();
        $class = ClassModel::withDeletedItems()
            ->findOrFail($class_id);
        $class->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
