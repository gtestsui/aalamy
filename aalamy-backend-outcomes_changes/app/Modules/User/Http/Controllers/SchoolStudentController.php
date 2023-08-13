<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\SchoolStudentResource;
use App\Modules\User\Http\Resources\StudentResource;
use App\Scopes\DefaultOrderByScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\SchoolClassManagement;
use Modules\Notification\Jobs\StudentCreatedByOthers\SendPasswordToStudentCreatedByOtherNotification;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\User\Http\Controllers\Classes\ImportStudentClasses\FileServices;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Http\Controllers\Classes\StudentClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\DTO\StudentData;
use Modules\User\Http\DTO\UserData;
use Modules\User\Http\Requests\Register\CreateStudentByOthersRequest;
use Modules\User\Http\Requests\School\DestroySchoolStudentRequest;
use Modules\User\Http\Requests\School\GetMySchoolStudentBySchoolStudentIdRequest;
use Modules\User\Http\Requests\School\GetMySchoolStudentRequest;
use Modules\User\Http\Requests\School\Imports\ImportSchoolStudentsRequest;
use Modules\User\Http\Requests\Student\UpdateStudentInformationRequest;
use Modules\User\Http\Requests\Student\UpdateStudentWithInformationRequest;
use Modules\User\Http\Requests\UserProfile\UpdateStudentBySchoolRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class SchoolStudentController extends Controller
{


    public function getAllMyStudentsDoesntBelongsToClass(GetMySchoolStudentRequest $request){
        $user = $request->user();
        $school = $user->School;
        $schoolClass = new SchoolClassManagement($school);
        $myClasses = $schoolClass->myClasses();
        $myClassesIds = $myClasses->pluck('id')->toArray();

        $myStudents = Student::search($request->key,[],['User'])
            ->belongsToSchool($school->id)
            ->whereDoesntHave('ClassStudents',function ($q)use ($myClassesIds){
                return $q->whereIn('class_id',$myClassesIds);
            })
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$user->account_type));
    }

    public function getAllMyStudentsDoesntBelongsToClassPaginate(GetMySchoolStudentRequest $request){
        $user = $request->user();
        $school = $user->School;
        $schoolClass = new SchoolClassManagement($school);
        $myClasses = $schoolClass->myClasses();
        $myClassesIds = $myClasses->pluck('id')->toArray();

        $myStudents = Student::search($request->key,[],['User'])
            ->belongsToSchool($school->id)
            ->whereDoesntHave('ClassStudents',function ($q)use ($myClassesIds){
                return $q->whereIn('class_id',$myClassesIds);
            })
            ->with('User')
            ->paginate(10);
        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$user->account_type));
    }


    public function getStudentAllInformation(GetMySchoolStudentBySchoolStudentIdRequest $request,$school_student_id){
        $user = $request->user();
        $schoolStudent = $request->getSchoolStudent();
        $student = Student::where('id',$schoolStudent->student_id)
            ->with([
                'User',
                'BasicInformation',
                'FamilyInformation',
                'OtherInformation',
                'SocialAndPersonalInformation',
            ])
            ->first();
        return ApiResponseClass::successResponse(new StudentResource($student));
    }

    public function createStudent(CreateStudentByOthersRequest $request,StudentClass $studentClass){
        $user = $request->user();
        DB::beginTransaction();

        $userData = UserData::fromRequest($request,'student');
        $studentData = StudentData::fromRequest($request,$user);
        if(isset($request->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);
            $school = School::findOrFail($teacher->school_id);
//            $studentData->merge(['created_by_teacher'=>$teacher->id]);
        }else{
            list(,$school) = UserServices::getAccountTypeAndObject($user);
//            $studentData->merge(['created_by_school'=>$school->id]);
        }


        $studentUser = $studentClass->create($studentData,$userData);
        $studentClass->createStudentAllInformation($studentData,$studentUser->Student);
        SchoolStudent::linkStudent($studentUser->Student->id,$school->id);
        DB::commit();
        dispatchJob(new SendPasswordToStudentCreatedByOtherNotification([
            [
                'email' => $userData->email,
                'password' => $userData->password,
            ],
        ]));
        return ApiResponseClass::successResponse(new UserResource($studentUser));

    }

    public function updateStudent(UpdateStudentBySchoolRequest $request,$school_student_id){
        $user = $request->user();

        $targetUser = $request->getTargetUser();
        $accountType = $targetUser->account_type;
        DB::beginTransaction();
        $userData = UserData::fromRequest($request,$accountType);
        $studentClass = new StudentClass();
        $studentData = $studentClass->getDataFromRequest($request);
        $targetUser = $studentClass->updateAccountWithPersonalInfo($studentData,$userData,$targetUser);

        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($targetUser,$user->account_type)));

    }

    public function updateStudentWithInformation(UpdateStudentWithInformationRequest $request,$school_student_id){
        DB::beginTransaction();

        $user = $request->user();

        $targetUser = $request->getTargetUser();
        $accountType = $targetUser->account_type;
        $userData = UserData::fromRequest($request,$accountType);
        $studentClass = new StudentClass();
        $studentData = $studentClass->getDataFromRequest($request);
        $targetUser = $studentClass->allowToUpdateTheEmail()
            ->updateAccountWithPersonalInfo($studentData,$userData,$targetUser);
        $studentClass->updateStudentInformation($studentData,$targetUser);

        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($targetUser,$user->account_type)));

    }

    public function import(ImportSchoolStudentsRequest $request,$class_id,$file_type){
        $user = $request->user();
        DB::beginTransaction();
        $fileClass = $request->getFileClass();
        $fileClass->import($request->{FileServices::getFileFieldName()},$user,$class_id);
        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }


    public function destroy(DestroySchoolStudentRequest $request,$schoolStudentId){
        $user = $request->user();
        DB::beginTransaction();
        $schoolStudent = $request->getSchoolStudent();
        $school = School::find($schoolStudent->school_id);

        $manageSchoolStudentClass = new StudentSchoolClass($school);
        $manageSchoolStudentClass->deleteSchoolStudent($schoolStudent);
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }


}
