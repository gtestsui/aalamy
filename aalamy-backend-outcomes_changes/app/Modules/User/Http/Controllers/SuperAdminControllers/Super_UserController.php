<?php

namespace Modules\User\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\DTO\UserData;
use Modules\User\Http\Requests\UserProfile\UpdateUserByAdminRequest;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;

class Super_UserController extends Controller
{


    public function getUserAccount(Request $request,$userId){
        $user = $request->user();
        $targetUser = User::withDeletedItems()
            ->findOrFail($userId);
        $targetUser->load(ucfirst($targetUser->account_type));
        return ApiResponseClass::successResponse(new UserResource($targetUser,$user->account_type));
    }


    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $user = User::with([
                'Educator',
                'School',
                'Student',
                'Parent',
            ])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new UserResource($user));

    }


    /**
     * get undeleted user
     * or get deleted user
     */
    public function getUserPaginateByType(Request $request,$account_type,$soft_delete=null){
        $user = $request->user();
//        $relationModelPaths = UserServices::getUserRelationModelPaths();
        DB::connection()->enableQueryLog();
        $users = User::where('account_type',$account_type)
            ->search($request->key,[],[
                ucfirst($account_type)
            ])
            ->with([ucfirst($account_type)=>function($query)use($account_type){
                return $query->withDeletedItems()
                    ->when($account_type=='student',function ($query){
                        return $query->with([
                            'ClassStudents'=>function($query){
                                return $query->active()->with('ClassModel.Level.BaseLevel');
                            }
                        ]);
                    });
            }])
            ->order($request->order_by_field,$request->order_type)
            ->trashed($soft_delete)
            ->paginate(config('panel.admin_paginate_num'));
//        dd(DB::getQueryLog());

        return ApiResponseClass::successResponse(UserResource::CustomCollection($users,$user->account_type));
//        $q->getModel()->getTable()
    }

    /*public function showUserDetails(ShowUserDetailsRequest $request,$userId){
        $targetUser = User::withDeletedItems()
        ->findOrFail($userId);
        $targetUser->load([ucfirst($targetUser->account_type)=>function($query){
            return $query->withoutGlobalScope(HasDeletedUserAsSoftScope::class);
        }]);
        $targetTeacherId = $request->teacher_id;
        $accountDetailsClass = UserServices::createAccountDetailsClassByType($targetUser->account_type,$targetUser,$targetTeacherId);
        $details = $accountDetailsClass->getDetails();
        return ApiResponseClass::successResponse($details);

    }*/

    public function activateOrDeActivate(Request $request,$userId){
        $userFromRequest = User::findOrFail($userId);
        $accountObject = $userFromRequest->{ucfirst($userFromRequest->account_type)};
        $accountObject->activateOrDeActivate();
        return ApiResponseClass::successResponse(['status' => $accountObject->is_active]);
    }

    public function update(UpdateUserByAdminRequest $request,$userId){
        $user = $request->user();

        $targetUser = User::findOrFail($userId);
        $accountType = $targetUser->account_type;
        DB::beginTransaction();
        $userData = UserData::fromRequest($request,$accountType);
        $childOfUserClassByType = UserServices::getObjectFromUserClassChildByType($accountType);
        $requestDataByType = $childOfUserClassByType->getDataFromRequest($request,$userData);
        $targetUser = $childOfUserClassByType
            ->allowToUpdateTheEmail()
            ->allowToUpdateThePassword()
            ->updateAccountWithPersonalInfo($requestDataByType,$userData,$targetUser);
        DB::commit();
        return ApiResponseClass::successResponse((new UserResource($targetUser,$user->account_type)));

    }


    public function softDeleteOrRestore(Request $request,$userId){
        DB::beginTransaction();
        DB::connection()->enableQueryLog();
        $userFromRequest = User::withDeletedItems()
        ->findOrFail($userId);
        $userFromRequest->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse($userFromRequest);

    }


    public function destroy(Request $request,$userId){
        $userFromRequest = User::withDeletedItems()
        ->findOrFail($userId);
        $userFromRequest->delete();
        return ApiResponseClass::deletedResponse();

    }



}
