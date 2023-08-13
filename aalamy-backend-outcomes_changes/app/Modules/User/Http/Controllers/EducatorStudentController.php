<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\Notification\Jobs\StudentCreatedByOthers\SendPasswordToStudentCreatedByOtherNotification;
use Modules\User\Http\Controllers\Classes\ImportStudentClasses\FileServices;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Http\DTO\StudentData;
use Modules\User\Http\DTO\UserData;
use Modules\User\Http\Requests\Educator\DestroyEducatorStudentRequest;
use Modules\User\Http\Requests\Educator\GetMyStudentRequest;
use Modules\User\Http\Requests\Educator\Imports\ImportEducatorStudentsRequest;
use Modules\User\Http\Requests\Register\CreateStudentByOthersRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class EducatorStudentController extends Controller
{

    public function myStudents(GetMyStudentRequest $request){
        $user = $request->user();
        $educator = $user->Educator;
        DB::connection()->enableQueryLog();
        $myStudents = Student::belongsToEducator($educator->id)
            ->search($request->key,[],[
                'User'
            ])
            ->with(['ClassStudents'=>function($query) use($educator){
                return $query->where('educator_id',$educator->id)
//                    ->active()
                    ->with('ClassModel.Level');
            },'User'])
            ->withDefinedEducatorStudent($educator)
            ->paginate(10);
//        dd(DB::getQueryLog());

        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$user->account_type));
    }

    public function getAllMyStudentsDoesntBelongsToClass(GetMyStudentRequest $request){
        $user = $request->user();
        $educator = $user->Educator;

        $educatorClass = new EducatorClassManagement($educator);
        $myClasses = $educatorClass->myClasses();
        $myClassesIds = $myClasses->pluck('id')->toArray();

        $myStudents = Student::belongsToEducator($educator->id)
            ->whereDoesntHave('ClassStudents',function ($q)use ($myClassesIds){
                return $q->whereIn('class_id',$myClassesIds);
            })
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$user->account_type));
    }

    public function getAllMyStudentsDoesntBelongsToDefinedRoster(GetMyStudentRequest $request,$roster_id){
        $user = $request->user();
        $educator = $user->Educator;

        $myStudents = Student::belongsToEducator($educator->id)
            ->where(function ($query)use ($roster_id){
                return $query->whereDoesntHave('ClassStudents')
                    ->orWhereHas('ClassStudents',function ($query)use($roster_id){
                        return $query->whereDoesntHave('RosterStudents',function ($query)use($roster_id){
                            return $query->where('roster_id',$roster_id);
                        });
                    });
            })
            ->with('User')
            ->get();

        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$user->account_type));
    }


    public function createStudent(CreateStudentByOthersRequest $request,StudentClass $studentClass){
        $user = $request->user();
        $user->load('Educator');
        DB::beginTransaction();
        $userData = UserData::fromRequest($request,'student');
        $studentData = StudentData::fromRequest($request)
            ->merge(['created_by_educator'=>$user->Educator->id]);

        $studentUser = $studentClass->create($studentData,$userData);
        EducatorStudent::linkStudent($studentUser->Student->id,$user->Educator->id);
        DB::commit();
        dispatchJob(new SendPasswordToStudentCreatedByOtherNotification([
            [
                'email' => $userData->email,
                'password' => $userData->password,
           ],
        ]));
        return ApiResponseClass::successResponse(new UserResource($studentUser));

    }

    public function import(ImportEducatorStudentsRequest $request,$roster_id,$file_type){
        $user = $request->user();
        DB::beginTransaction();
        $fileClass = $request->getFileClass();
        $fileClass->import($request->{FileServices::getFileFieldName()},$user,$roster_id);
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }

    public function destroy(DestroyEducatorStudentRequest $request,$educator_student_id){
        $user = $request->user();
        $educatorStudent = $request->getEducatorStudent();
        $educator = Educator::find($educatorStudent->educator_id);

        $manageEducatorStudentClass = new StudentEducatorClass($educator);
        $manageEducatorStudentClass->deleteSchoolStudent($educatorStudent);
        return ApiResponseClass::deletedResponse();
    }


}
