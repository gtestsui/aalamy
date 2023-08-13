<?php

namespace Modules\User\Http\Controllers;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\DTO\StudentData;
use Modules\User\Http\Requests\Educator\GetMyTeacherAccountRequest;
use Modules\User\Http\Requests\Student\StoreStudentRequest;
use Modules\User\Models\Educator;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class TeacherController extends Controller
{

    public function update(Request $request ,$my_teacher_id){
        $user = $request->user();
        $teacher = Teacher::findOrFail($my_teacher_id);
        UserServices::checkThisTeacherItsMe($user->id,$teacher);
        $teacher->update([
            'bio' => $request->bio
        ]);
        return ApiResponseClass::successResponse(new TeacherResource($teacher));
    }

    public function myTeacherAccounts(GetMyTeacherAccountRequest $request){
        $user = $request->user();
        $teachers = Teacher::where('user_id',$user->id)
            ->with('School')
            ->active()
            ->get();
        return ApiResponseClass::successResponse(TeacherResource::collection($teachers));
    }


//    /**
//     * @note return my teacher with object from school accounts if im educator
//     * and the teacher with object from user inside my school if im school
//     */
//    public function myTeacherAccounts(GetMyTeacherAccountRequest $request){
//        $user = $request->user();
//        $myTeachers = Teacher::belongToMe($user)
////            ->with('School','User')
//            ->get();
//
//        return ApiResponseClass::successResponse(TeacherResource::collection($myTeachers));
//    }



}
