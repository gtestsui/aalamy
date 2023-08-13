<?php

namespace Modules\User\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClassModule\Http\Resources\ClassStudentResource;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Http\Controllers\Classes\AccountDetails\StudentDetailsClass;
use Modules\User\Http\Requests\SuperAdminRequests\ShowUserDetailsRequest;
use Modules\User\Models\Student;

class Super_StudentController extends Controller
{

    public function getDetails(ShowUserDetailsRequest $request,$parent_id){
        $student = Student::findOrFail($parent_id);
        $student->load('User');
        $detailsClass = new StudentDetailsClass($student);
        $details = $detailsClass->getDetails();

        return ApiResponseClass::successResponse($details);

    }

    public function getByClassId(Request $request,$class_id){
        $user = $request->user();
        $classStudents = ClassStudent::active()
            ->where('class_id',$class_id)
            ->with([
                'ClassModel.Level',
                'Student.User',
            ])
            ->paginate(10);
        return ApiResponseClass::successResponse(ClassStudentResource::CustomCollection($classStudents,$user->account_type));
    }



}
