<?php

namespace Modules\TeacherPermission\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\TeacherPermission\Http\Requests\Permission\GetPermissionsRequest;
use Modules\TeacherPermission\Http\Requests\Permission\GetPermissionsWithDefinedTeacherPermissionsRequest;
use Modules\TeacherPermission\Http\Resources\PermissionResource;
use Modules\TeacherPermission\Models\Permission;


class PermissionController extends Controller
{


    public function getAllPermissions(GetPermissionsRequest $request){
        $permissions = Permission::get();

        return ApiResponseClass::successResponse(PermissionResource::collection($permissions));
    }


    public function getAllPermissionsWithDefinedTeacherPermissions(GetPermissionsWithDefinedTeacherPermissionsRequest $request,$teacher_id){
        $permissions = Permission::with(['PermissionTeachers'=>function($query)use($teacher_id){
                return $query->where('teacher_id',$teacher_id);
            }])
            ->get();

        return ApiResponseClass::successResponse(PermissionResource::collection($permissions));


    }





}
