<?php

namespace Modules\ClassModule\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Http\DTO\ClassInfoData;
use Modules\ClassModule\Http\Requests\ClassInfo\DeleteClassInfoRequest;
use Modules\ClassModule\Http\Requests\ClassInfo\GetMyClassInfoRequest;
use Modules\ClassModule\Http\Requests\ClassInfo\StoreClassInfoRequest;
use Modules\ClassModule\Http\Requests\ClassInfo\StoreClassInfoWithManyLevelSubjectRequest;
use Modules\ClassModule\Http\Requests\ClassInfo\UpdateClassInfoRequest;
use Modules\ClassModule\Http\Resources\ClassInfoResource;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\ClassModule\Models\ClassInfo;
use Modules\User\Http\Controllers\Classes\UserServices;

class ClassInfoController extends Controller
{

    public function getByClassId($class_id){
        $classInfos = ClassInfo::where('class_id',$class_id)
            ->with(['School.User','Educator','Teacher.User','LevelSubject.Subject'])
            ->get();
        return ApiResponseClass::successResponse(ClassInfoResource::collection($classInfos));
    }

    public function getClassInfoDistinctOnTeacherId(GetMyClassInfoRequest $request,$class_id){
        $user = $request->user();

        $classManagment = ClassManagementFactory::create($user);
        $classInfos = $classManagment->getMyClassInfoByClassIdQuery($class_id)
            ->with(['Teacher.User','Educator.User'])
            ->get();

//        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
//        if ($accountType == 'teacher'){
//            $classInfos = ClassInfo::where('class_id',$class_id)
//                ->where('teacher_id',$accountObject->id)
//                ->with(['Teacher.User'])
//                ->get();
//        }else{
//            $classInfos = ClassInfo::where('class_id',$class_id)
//                ->with(['Teacher.User','Educator.User'])
//                ->get();
//        }

        $selectedTeacherIds = [];
        foreach ($classInfos as $key => $classInfo){
            if(in_array($classInfo->teacher_id,$selectedTeacherIds))
                $classInfos->forget($key);
            else
                $selectedTeacherIds[] = $classInfo->teacher_id;
        }
        return ApiResponseClass::successResponse(ClassInfoResource::collection($classInfos));
    }

    public function store(StoreClassInfoRequest $request,$class_id){
        $user = $request->user();
        $classInfoData = ClassInfoData::fromRequest($request,$user);
        $classInfo = ClassInfo::where('class_id',$classInfoData->class_id)
            ->where('level_subject_id',$classInfoData->level_subject_id)
            ->where('teacher_id',$classInfoData->teacher_id)
            ->first();
        if(is_null($classInfo)){
            $classInfo = ClassInfo::create($classInfoData->all());
        }
        return ApiResponseClass::successResponse(new ClassInfoResource($classInfo));
    }

    public function storeWithManyLevelSubject(StoreClassInfoWithManyLevelSubjectRequest $request,$class_id){
        $user = $request->user();
        $isSchool = UserServices::isSchool($user);
        foreach ($request->level_subject_ids as $levelSubjectId){
            $classInfo = ClassInfo::where('class_id',$class_id)
                ->where('level_subject_id',$levelSubjectId)
                ->where('teacher_id',$request->teacher_id)
                ->first();
            if(is_null($classInfo)) {
                $classInfo = ClassInfo::create([
                    'class_id' => $class_id,
                    'teacher_id' => $isSchool ? (int)$request->teacher_id : null,
                    'educator_id' => $isSchool ? null : $user->Educator->id,
                    'school_id' => $isSchool ? $user->School->id : null,
                    'level_subject_id' => $levelSubjectId,
                ]);
            }

        }

        return ApiResponseClass::successResponse(new ClassInfoResource($classInfo));
    }


    public function update(UpdateClassInfoRequest $request,$class_id,$id){
        $user = $request->user();
        $classInfoData = ClassInfoData::fromRequest($request,$user);
        $classInfo = ClassInfo::where('class_id',$class_id)
            ->findOrFail($id);
        $classInfo->update($classInfoData->initializeForUpdate($classInfoData));
        return ApiResponseClass::successResponse(new ClassInfoResource($classInfo));
    }

    public function softDelete(DeleteClassInfoRequest $request,$class_id,$id){
        $classInfo = ClassInfo::findOrFail($id);
        $classInfo->softDeleteObject();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DeleteClassInfoRequest $request,$id){
        $classInfo = ClassInfo::findOrFail($id);
        $classInfo->delete();
        return ApiResponseClass::deletedResponse();
    }

}
