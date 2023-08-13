<?php

namespace Modules\User\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\SchoolStudentResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Models\Level;
use Modules\User\Http\Controllers\Classes\AccountDetails\SchoolDetailsClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Http\Requests\SuperAdminRequests\ShowUserDetailsRequest;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class Super_SchoolController extends Controller
{

    public function getDetails(ShowUserDetailsRequest $request,$school_id){
        $school = School::findOrFail($school_id);
        $school->load('User');
        $schoolDetailsClass = new SchoolDetailsClass($school);
        $details = $schoolDetailsClass->getDetails();

        return ApiResponseClass::successResponse($details);

    }

    public function getStudents(Request $request,$school_id){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolStudentManageClass = new StudentSchoolClass($school);
//        $schoolStudents = $schoolStudentManageClass->myStudentsWithRelationPaginate();
        $schoolStudents = $schoolStudentManageClass->myStudentsQuery()
            ->search($request->key,[],[
                'Student.User'
            ])
            ->with('Student.User')
            ->paginate(10);

        return ApiResponseClass::successResponse(SchoolStudentResource::collection($schoolStudents));
    }

    public function getStudentParents(Request $request,$school_id){
        $school = School::findOrFail($school_id);
        $schoolStudentManageClass = new StudentSchoolClass($school);
//        $parents = $schoolStudentManageClass->myStudentParentsAllWithUserObject();
        $parents = $schoolStudentManageClass->myStudentParentsQuery()
            ->search($request->key,[],[
                'User'
            ])
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(ParentResource::collection($parents));

    }

    public function getTeachers(Request $request,$school_id){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $teachers = Teacher::where('school_id',$school->id)
            ->search($request->key,[],[
                'User'
            ])
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(TeacherResource::collection($teachers));
    }





}
