<?php

namespace Modules\User\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\EducatorStudentResource;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\AccountDetails\EducatorDetailsClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Requests\SuperAdminRequests\ShowUserDetailsRequest;
use Modules\User\Models\Educator;
use Modules\User\Models\Teacher;

class Super_EducatorController extends Controller
{

    public function getDetails(ShowUserDetailsRequest $request,$educator_id){
        $educator = Educator::findOrFail($educator_id);
        $educator->load('User');
        $educatorDetailsClass = new EducatorDetailsClass($educator);
        $details = $educatorDetailsClass->getDetails();

        return ApiResponseClass::successResponse($details);

    }

    public function getStudents(Request $request,$educator_id){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorStudentManageClass = new StudentEducatorClass($educator);
//        $educatorStudents = $educatorStudentManageClass->myStudentsWithRelationPaginate();
        $educatorStudents = $educatorStudentManageClass->myStudentsQuery()
            ->search($request->key,[],[
                'Student.User'
            ])
            ->with('Student.User')
            ->paginate(10);

        return ApiResponseClass::successResponse(EducatorStudentResource::collection($educatorStudents));
    }

    public function getStudentParents(Request $request,$educator_id){
        $educator = Educator::findOrFail($educator_id);
        $educatorStudentManageClass = new StudentEducatorClass($educator);
//        $parents = $educatorStudentManageClass->myStudentParentsAllWithUserObject();
        $parents = $educatorStudentManageClass->myStudentParentsQuery()
            ->search($request->key,[],[
                'User'
            ])
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(ParentResource::collection($parents));

    }

    public function getTeachers(Request $request,$educator_id){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $teachers = Teacher::where('user_id',$educator->user_id)
            ->search($request->key,[],[
                'User'
            ])
            ->with('User')
            ->get();
        return ApiResponseClass::successResponse(TeacherResource::collection($teachers));
    }





}
