<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\ParentStudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Jobs\Parent\SendParentStudentLinkNotification;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentManagementFactory;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\ParentStudent\DestroyParentStudentRequest;
use Modules\User\Http\Requests\ParentStudent\GetMyParentStudentRequest;
use Modules\User\Http\Requests\ParentStudent\GetParentsHaveStudentsBelongToClassRequest;
use Modules\User\Http\Requests\ParentStudent\GetParentsHaveStudentsBelongToMeRequest;
use Modules\User\Http\Requests\ParentStudent\SendParentStudentLinkRequest;
use Modules\User\Http\Requests\ParentStudent\StoreParentStudentRequest;

class ParentStudentController extends Controller
{

    public function myStudents(GetMyParentStudentRequest $request){
        $user = $request->user();
        $studentParentClass = new StudentParentClass($user->Parent);
        $myStudents = $studentParentClass->myStudentsWithRelation();
        return ApiResponseClass::successResponse(ParentStudentResource::collection($myStudents));
    }

    public function getParentHaveStudentsBelongToMe(GetParentsHaveStudentsBelongToMeRequest $request){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);

        $studentManageClass = StudentManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
//        $studentSchoolClass = new StudentSchoolClass($school);
        $parents = $studentManageClass->myStudentParentsPaginate();

//        $parents = $studentSchoolClass->myStudentParents();
        return ApiResponseClass::successResponse(ParentResource::collection($parents));

    }


    public function getParentHaveStudentBelongToClass(GetParentsHaveStudentsBelongToClassRequest $request,$class_id){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);

        $studentManageClass = StudentManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
        $parents = $studentManageClass->myStudentParentsByClassId($class_id);

        return ApiResponseClass::successResponse(ParentResource::collection($parents));
    }

    public function store(StoreParentStudentRequest $request){
        $user = $request->user();
        $user->load('Parent');
        DB::beginTransaction();
        $studentParentClass = new StudentParentClass($user->Parent);
        $studentsArray = $studentParentClass->addStudentsToParent($request->parent_codes);
        DB::commit();
        return ApiResponseClass::successResponse($studentsArray);
    }

    public function sendParentLink(SendParentStudentLinkRequest $request,$student_id){
        $user = $request->user();
        $user->load(ucfirst($user->account_type));
        $student = $request->getStudent();
//        UserServices::checkParentEmail($student);
        dispatchJob(new SendParentStudentLinkNotification($user,$student,$request->email));
        return ApiResponseClass::successMsgResponse();

    }

    public function destroy(DestroyParentStudentRequest $request,$parent_student_id){
        $parentStudent = $request->getParentStudent();
        $parentStudent->delete();
        return ApiResponseClass::deletedResponse();
    }


}
