<?php

namespace Modules\TeacherPermission\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\TeacherPermission\Http\Requests\PermissionTeacher\AddOrDeletePermissionsToTeacherRequest;
use Modules\TeacherPermission\Http\Requests\PermissionTeacher\GetMyAllowerPermissionsRequest;
use Modules\TeacherPermission\Http\Requests\PermissionTeacher\GetTeacherPermissionsByTeacherIdRequest;
use Modules\TeacherPermission\Http\Resources\PermissionTeacherResource;
use Modules\TeacherPermission\Models\PermissionTeacher;
use Modules\User\Http\Controllers\Classes\UserServices;


class PermissionTeacherController extends Controller
{


    public function getMyAllowedPermissions(GetMyAllowerPermissionsRequest $request){
        $user = $request->user();
        $teacherPermissions = PermissionTeacher::where('teacher_id',$request->my_teacher_id)
            ->with('Permission')
            ->get();

        return ApiResponseClass::successResponse(PermissionTeacherResource::collection($teacherPermissions));

    }

    public function getTeacherPermissionsByTeacherId(GetTeacherPermissionsByTeacherIdRequest $request,$teacher_id){
        $teacherPermissions = PermissionTeacher::where('teacher_id',$teacher_id)
            ->with('Permission')
            ->get();

        return ApiResponseClass::successResponse(PermissionTeacherResource::collection($teacherPermissions));
    }


    public function addOrDeletePermissionsToTeacher(AddOrDeletePermissionsToTeacherRequest $request,$teacher_id){
        $user = $request->user();
        DB::beginTransaction();
        list(,$school) = UserServices::getAccountTypeAndObject($user);

        $foundTeacherPermissions = PermissionTeacher::where('teacher_id',$teacher_id)
            ->delete();

        if(isset($request->permissions_ids)){
            $arrayForCreate = [];
            foreach ($request->permissions_ids as $permissionId){
                $arrayForCreate [] = [
                    'school_id' => $school->id,
                    'teacher_id' => $teacher_id,
                    'permission_id' => $permissionId,
                    'created_at' => Carbon::now(),
                ];

            }
            PermissionTeacher::insert($arrayForCreate);
        }

        DB::commit();
        return ApiResponseClass::successMsgResponse();
    }








}
