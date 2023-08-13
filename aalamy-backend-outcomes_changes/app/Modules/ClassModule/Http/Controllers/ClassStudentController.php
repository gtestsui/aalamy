<?php

namespace Modules\ClassModule\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent\ClassStudentManagementFactory;
use Modules\ClassModule\Http\DTO\ClassData;
use Modules\ClassModule\Http\Requests\ClassRequest\DestroyClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\GetClassByLevelIdRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\StoreClassRequest;
use Modules\ClassModule\Http\Requests\ClassRequest\UpdateClassRequest;
use Modules\ClassModule\Http\Requests\ClassStudent\AddMoreThanStudentRequest;
use Modules\ClassModule\Http\Requests\ClassStudent\AddStudentToClassOrMoveRequest;
use Modules\ClassModule\Http\Requests\ClassStudent\DestroyMoreThanStudentRequest;
use Modules\ClassModule\Http\Requests\ClassStudent\GetClassStudentRequest;
use Modules\ClassModule\Http\Resources\ClassResource;
use Modules\ClassModule\Http\Resources\ClassStudentResource;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class ClassStudentController extends Controller
{

    /**
     * get ClassStudents by class id and filter by email or FullName
     */
    public function getByClassId(GetClassStudentRequest $request ,$class_id){
        $user = $request->user();
        DB::connection()->enableQueryLog();
        $classStudentsQuery = ClassStudent::query();
        $classStudentsQuery->where('class_id',$class_id)
            ->with(['Student.User','Educator','Teacher','School','ClassModel.Level',/*'User'*/])
                ->where(function ($q)use ($request){
                $q->search($request->key,[],['Student.User']);

            })
            ->active();

//        if(isset($request->key))
//            $classStudentsQuery->whereHas('Student',function ($query)use($request){
//                //this will search in Student and in User field
////                return $query->search($request->key,[],[
////                    'User'
////                ]);
//                return $query->search($request->key,['User.[fullName,email]'],[],[
//                    'fullName' => DB::raw('CONCAT(fname," ",lname)'),
//                ]);
//            });

        $classStudents = $classStudentsQuery->get();
//dd(DB::getQueryLog());
        return ApiResponseClass::successResponse(ClassStudentResource::CustomCollection($classStudents,$user->account_type));
    }

    /**
     * get the shared ids between my student and the student in request
     * then we add them to my class
     */
    public function addMoreThanStudent(AddMoreThanStudentRequest $request,$class_id){
        $user = $request->user();
        DB::beginTransaction();
//        $manageClass = ClassServices::createManageClassStudentByType($user->account_type,$user,$request->my_teacher_id);
        $manageClass = ClassStudentManagementFactory::create($user,$request->my_teacher_id);
        $manageClass->addMoreThanStudent($class_id,$request->student_ids);
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }

    public function addStudentToClassOrMove(AddStudentToClassOrMoveRequest $request,$class_id,$student_id){
        DB::beginTransaction();
    
    	$user = $request->user();
//        $manageClass = ClassServices::createManageClassStudentByType($user->account_type,$user,$request->my_teacher_id);
        $manageClass = ClassStudentManagementFactory::create($user,$request->my_teacher_id);
        $manageClass->addStudentToClassOrMoveToAnotherClass($class_id,$student_id);
    	DB::commit();
        return ApiResponseClass::successMsgResponse();

    }


    public function softDeleteMoreThanStudent(Request $request,$class_id){
        $user = $request->user();
        DB::beginTransaction();
        $classStudents = ClassStudent::whereIn('id',$request->class_student_ids)
            ->where('class_id',$class_id)->get();
        foreach ($classStudents as $classStudent){
            $classStudent->softDeleteObject();
        }
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

    public function destroyMoreThanStudent(DestroyMoreThanStudentRequest $request,$class_id){
        $user = $request->user();
//        foreach ($request->class_student_ids as $classStudentId)
        ClassStudent::whereIn('id',$request->class_student_ids)
            ->where('class_id',$class_id)->delete();
        return ApiResponseClass::deletedResponse();

    }


}
