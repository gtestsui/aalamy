<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\EducatorStudentResource;
use App\Modules\User\Http\Resources\SchoolStudentResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\DTO\StudentData;
use Modules\User\Http\DTO\UserData;
use Modules\User\Http\Requests\Student\GetMyActiveSchoolRequest;
use Modules\User\Http\Requests\Student\GetStudentBelongsToMeRequest;
use Modules\User\Http\Requests\Student\StoreStudentRequest;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Http\Controllers\UserController;
use Modules\User\Models\User;

class StudentController extends Controller
{

    //search for students
    public function search(Request $request){
        $user = $request->user();
        DB::connection()->enableQueryLog();
        $students = Student::search($request->key,[],[
            'User'
        ])
        ->when(UserServices::isSchool($user),function ($query)use($user){
            $school = $user->School;
            return $query->WithDefinedSchoolRequest($school)
                ->withDefinedSchoolStudent($school);
        })
        ->when(UserServices::isEducator($user),function ($query)use($user){
            $educator = $user->Educator;
            return $query->withDefinedEducatorRosterStudentRequest($educator)
                ->withDefinedEducatorStudent($educator);
        })
        ->with('User')
        ->paginate(10);
//        dd(DB::getQueryLog());
//return $students;
        return ApiResponseClass::successResponse(StudentResource::collection($students));
    }

    public function getMyActiveSchool(GetMyActiveSchoolRequest $request){
        $user = $request->user();
        $user->load('Student.SchoolStudent');
        if(is_null($user->Student->SchoolStudent)){
            return ApiResponseClass::successResponse(null);

        }else{
            $user->Student->SchoolStudent->load('School');
            $schoolStudent = $user->Student->SchoolStudent;
            return ApiResponseClass::successResponse(new SchoolStudentResource($schoolStudent));

        }
    }

    public function getMyActiveEducators(GetMyActiveSchoolRequest $request){
        $user = $request->user();
        $user->load('Student.EducatorStudents.Educator.User');
        $educatorStudents = $user->Student->EducatorStudents;
        return ApiResponseClass::successResponse(EducatorStudentResource::collection($educatorStudents));
    }

    public function getStudentsBelongsToMePaginate(GetStudentBelongsToMeRequest $request){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);

        $studentManageClass = StudentManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
        $myStudentIds = $studentManageClass->myStudentIds();

        $myStudents = Student::whereIn('id',$myStudentIds)
            ->search($request->key,[],['User'])
            ->with(['ClassStudents'=>function($query) use($accountType,$accountObject){
                return $query->where($accountType.'_id',$accountObject->id)
//                ->active()
                    ->with('ClassModel.Level');
            },'User'/*,'SchoolStudent'*/])
            ->when($accountType == 'school',function ($query)use($accountObject){
                return $query->withDefinedSchoolStudent($accountObject);
            })
            ->when($accountType == 'educator',function ($query)use($accountObject){
                return $query->withDefinedEducatorStudent($accountObject);
            })
            ->when($accountType == 'teacher',function ($query)use($accountObject){
                $school = School::findOrFail($accountObject->school_id);
                return $query->withDefinedSchoolStudent($school);
            })
            ->paginate(10);

        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$accountType));

    }


    /**
     * we used key_search for search because in myStudentIds its depend on the default search key
     * so that will return invalid results
     */
    public function getStudentsBelongsToMeWithParentsInfo(GetStudentBelongsToMeRequest $request){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);

        $studentManageClass = StudentManagementFactory::createByAccountTypeAndObject($accountType,$accountObject);
        $myStudentIds = $studentManageClass->myStudentIds();

        $myStudents = Student::whereIn('id',$myStudentIds)
            ->search($request->search_key,[],['User','ParentStudents.Parent.User'])
            ->with(['User','ParentStudents.Parent.User'])
            ->paginate(10);

        return ApiResponseClass::successResponse(StudentResource::CustomCollection($myStudents,$accountType));

    }


}
