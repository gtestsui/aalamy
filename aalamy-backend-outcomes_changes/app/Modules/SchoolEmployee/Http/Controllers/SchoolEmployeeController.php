<?php

namespace Modules\SchoolEmployee\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\SchoolEmployee\Http\Controllers\Classes\ManageEmployee\SchoolEmployeeManagementFactory;
use Modules\SchoolEmployee\Http\Controllers\Classes\ManageEmployee\TeacherEmployeeClass;
use Modules\SchoolEmployee\Http\Requests\SchoolEmployee\DestroySchoolEmployeeRequest;
use Modules\SchoolEmployee\Http\Requests\SchoolEmployee\ShowSchoolEmployeeRequest;
use Modules\SchoolEmployee\Http\Requests\SchoolEmployee\StoreFoundTeacherInSchoolEmployeeRequest;
use Modules\SchoolEmployee\Http\Requests\SchoolEmployee\StoreSchoolEmployeeRequest;
use Modules\SchoolEmployee\Http\Requests\SchoolEmployee\UpdateSchoolEmployeeRequest;
use Modules\SchoolEmployee\Http\Resources\SchoolEmployeeResource;
use Modules\SchoolEmployee\Models\SchoolEmployee;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class SchoolEmployeeController extends Controller
{


    public function getMyEmployees(Request $request){
        $user = $request->user();
        list($accountType,$school) = UserServices::getAccountTypeAndObject($user);

        $employees = SchoolEmployee::my($school->id)->get();
        return ApiResponseClass::successResponse(
            SchoolEmployeeResource::collection($employees)
        );
    }

    public function getTeacherDoesntBelongsToEmployee(Request $request){
        $user = $request->user();
        $user->load('School');
        $teachers = Teacher::where('school_id',$user->School->id)
            ->whereDoesntHave('SchoolEmployees',function ($query)use ($user){
                return $query->where('school_id',$user->School->id);
            })
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(
            TeacherResource::collection($teachers)
        );
    }

    public function getMyTeacherInfo(Request $request,$teacher_id){
        $user = $request->user();
        $user->load('School');
        $teacher = Teacher::where('school_id',$user->School->id)
            ->with('User')
            ->findOrFail($teacher_id);
        return ApiResponseClass::successResponse(
            new TeacherResource($teacher)
        );
    }

    public function show(ShowSchoolEmployeeRequest $request,$id){
        $user = $request->user();
        $user->load('School');
        $employee = SchoolEmployee::my($user->School->id)
            ->with([
                'Certificates'
            ])
            ->findOrFail($id);
        return ApiResponseClass::successResponse(
            new SchoolEmployeeResource($employee)
        );

    }

    public function store(StoreSchoolEmployeeRequest $request){
        $schoolUser = $request->user();
        DB::beginTransaction();
        $employeeClass = SchoolEmployeeManagementFactory::create($request->type);
        $employeeClass->create($request);
        DB::commit();
        return ApiResponseClass::successMsgResponse();

    }

    public function storeFoundTeacher(StoreFoundTeacherInSchoolEmployeeRequest $request){
        $user = $request->user();

        DB::beginTransaction();
        $teacher = $request->getTeacher();
        $teacherEmployeeClass = new TeacherEmployeeClass();
        $teacherEmployeeClass->createFoundTeacher($request,$teacher);

        DB::commit();
        return ApiResponseClass::successMsgResponse();

    }


    public function update(UpdateSchoolEmployeeRequest $request,$id){
        $employee = $request->getSchoolEmployee();
        $employeeClass = SchoolEmployeeManagementFactory::create($employee->type);
        $employeeClass->update($employee,$request);
        return ApiResponseClass::successResponse(
            new SchoolEmployeeResource($employee)
        );

    }


    public function destroy(DestroySchoolEmployeeRequest $request,$id){
        $employee = $request->getSchoolEmployee();
        $employee->softDeleteObject();
        return ApiResponseClass::deletedResponse();

    }

}
