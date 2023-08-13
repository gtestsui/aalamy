<?php

namespace Modules\ClassModule\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\TeacherClassManagement;
use Modules\ClassModule\Http\DTO\ClassData;
use Modules\ClassModule\Http\DTO\ClassWithClassInfoData;
use Modules\ClassModule\Http\Requests\ClassRequest\CheckIfImTeacherInsideClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\DestroyClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\GetClassByLevelIdRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\ShowClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\StoreClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\UpdateClassRequest;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\ClassModule\Http\Requests\ClassRequest\GetMyClassesRequest;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;

class ClassController extends Controller
{

    public function myClasses(GetMyClassesRequest $request){
        $user = $request->user();
        $manageClass = ClassManagementFactory::create($user,$request->my_teacher_id);
        $myLevels = $manageClass->myClassesWithInfo();

//        $manageLevelClass = LevelServices::createManageLevelClassByType($user->account_type,$user,$request->my_teacher_id);
//        $myLevels = $manageLevelClass-myClasses();
        return ApiResponseClass::successResponse(ClassResource::collection($myLevels));

    }

    public function checkIfImTeacherInsideClass(CheckIfImTeacherInsideClassRequest $request,$id){
        $user = $request->user();

        list(,$teacher) = UserServices::getAccountTypeAndObject($user);
        $teacherClassManagment = new TeacherClassManagement($teacher);
        $class = $teacherClassManagment->ignorePermissions()
            ->myClassesById($id);


        return ApiResponseClass::successResponse([
            'status' => !is_null($class)?true:false
        ]);
    }

    public function show(ShowClassRequest $request ,$id){
        $user = $request->user();
        $class = $request->getClass();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        if($accountType == 'teacher'){
            $class->load(['Level','ClassInfos'=>function($query)use($accountObject){
                return $query->with(['School.User','Teacher.User','Educator.User','LevelSubject.Subject'])
                    ->where('teacher_id',$accountObject->id);
            }]);
        }else{
            $class->load(['Level','ClassInfos'=>function($query){
                return $query->with(['School.User','Teacher.User','Educator.User','LevelSubject.Subject']);
            }]);
        }

        return ApiResponseClass::successResponse(new ClassResource($class));
    }

    public function getByLevelId(GetClassByLevelIdRequest $request,$levelId){
        $classes = ClassModel::with(['ClassInfos'])
            ->where('level_id',$levelId)
            ->get();
        return ApiResponseClass::successResponse(ClassResource::collection($classes));
    }

    public function store(StoreClassRequest $request){
        $user = $request->user();
        $classData = ClassData::fromRequest($request);
        $class = ClassModel::create($classData->all());
        $class->load('Level');
        return ApiResponseClass::successResponse(new ClassResource($class));
    }

    public function createWithClassInfo(StoreClassRequest $request){
        DB::beginTransaction();
        $user = $request->user();
        $classData = ClassWithClassInfoData::fromRequest($request);
        $class = ClassModel::create($classData->all());
        $class->load('Level');

        ClassServices::createMoreThanClassInfo($class->id,$classData);

        DB::commit();
        return ApiResponseClass::successResponse(new ClassResource($class));
    }

    public function update(UpdateClassRequest $request,$id){
        $user = $request->user();
        $class = $request->getClass();
        $classData = ClassData::fromRequest($request);
        $class->update($classData->initializeForUpdate($classData));
        $class->load('Level');
        return ApiResponseClass::successResponse(new ClassResource($class));
    }

    public function destroy(DestroyClassRequest $request,$id){
        $class = $request->getClass();
        $class->delete();
        return ApiResponseClass::deletedResponse();
    }



}
