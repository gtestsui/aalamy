<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\DTO\SchoolData;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\SchoolClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\School\DeleteTeacherFromMySchoolRequest;
use Modules\User\Http\Requests\School\GetAllMyTeachersByClassIdRequest;
use Modules\User\Http\Requests\School\GetMyTeacherRequest;
use Modules\User\Http\Requests\School\UpdateSchoolRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class SchoolController extends Controller
{

    public function update(UpdateSchoolRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $schoolData = SchoolData::fromRequest($request);
        $schoolClass = new SchoolClass();
        $user = $schoolClass->update($schoolData,$user);

        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($user)));

    }

    public function getMyTeachers(GetMyTeacherRequest $request){
        DB::connection()->enableQueryLog();
        $user = $request->user();
        $school = $user->School;
        $myTeachers = Teacher::where('school_id',$school->id)
            ->search($request->key,[],[
                'User'
            ])
            ->with(['ClassInfos'=>function($query){
                return $query->with(['ClassModel','LevelSubject'=>function($q){
                    return $q->with(['Level','Subject']);
                }]);
            },'User'])
            ->paginate(10);
        return ApiResponseClass::successResponse(TeacherResource::collection($myTeachers));

    }

    public function getAllMyTeachers(GetMyTeacherRequest $request){
        $user = $request->user();
        $school = $user->School;
        $myTeachers = Teacher::where('school_id',$school->id)
            ->with(['User'])
            ->get();
        return ApiResponseClass::successResponse(TeacherResource::collection($myTeachers));

    }

    public function getAllMyTeachersByClassId(GetAllMyTeachersByClassIdRequest $request,$class_id){
        $teachers =  Teacher::whereHas('ClassInfos',function ($query)use ($class_id){
                return $query->where('class_id',$class_id);
            })
            ->with(['User'])
            ->active()
            ->get();
        return ApiResponseClass::successResponse(TeacherResource::collection($teachers));
    }


    public function search(Request $request){
        $user = $request->user();

        //we can call search without the array in second parameter
        $schools = School::search($request->key,[],[
            'User'
        ])
        ->when(UserServices::isStudent($user),function ($query)use ($user){//to check if the user has request relations with this school
            $student = $user->Student;
            return $query->withDefinedStudentRequest($student)
                         ->withDefinedSchoolStudent($student);
        })
        ->when(UserServices::isEducator($user),function ($query)use($user){
            $educator = $user->Educator;
            return $query->withDefinedTeacherRequest($educator)
                         ->withDefinedTeacher($user);
        })
        ->paginate(10);

        return ApiResponseClass::successResponse(SchoolResource::collection($schools));
    }

    public function deleteTeacherFromMySchool(DeleteTeacherFromMySchoolRequest $request,$teacher_id){
        $user = $request->user();
        $user->load('School');
        DB::beginTransaction();
        $teacher = Teacher::where('school_id',$user->School->id)
            ->findOrFail($teacher_id);
        $teacher->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }




}
