<?php

namespace Modules\Roster\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Level\Http\Controllers\Classes\ManageUnit\UnitManagementFactory;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\EducatorRosterClass;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\SchoolRosterClass;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Http\DTO\RosterData;
use Modules\Roster\Http\Requests\Roster\CloseOrUnCloseRosterRequest;
use Modules\Roster\Http\Requests\Roster\DestroyRosterRequest;
use Modules\Roster\Http\Requests\Roster\GetMyRostersRequest;
use Modules\Roster\Http\Requests\Roster\StoreRosterRequest;
use Modules\Roster\Http\Requests\Roster\UpdateRosterRequest;
use Modules\Roster\Http\Resources\RosterResource;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class Super_RosterController extends Controller
{



    public function paginate(Request $request,$soft_delete=null){
        $user = $request->user();
        $rosters = Roster::search($request->key)
            ->trashed($soft_delete)
            ->with(['ClassInfo'=>function ($query){
                return $query->with([
                    'School','Educator.User','Teacher.User'
                ]);
            }])
            ->paginate(config('panel.admin_paginate_num'));
        return ApiResponseClass::successResponse(RosterResource::CustomCollection($rosters,$user->account_type));
    }


    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $roster = Roster::with(['ClassInfo'=>function ($query){
                return $query->with([
                    'School','Educator.User','Teacher.User'
                ]);
            }])
            ->findOrFail($id);


        return ApiResponseClass::successResponse(new RosterResource($roster));

    }

    public function byClassId(Request $request,$class_id){
        $classInfoIds = ClassInfo::where('class_id',$class_id)->pluck('id')->toArray();
        $rosters = Roster::whereIn('class_info_id',$classInfoIds)
//            ->with('ClassInfo')
            ->paginate(config('panel.admin_paginate_num'));

        return ApiResponseClass::successResponse(RosterResource::collection($rosters));
    }

    public function getEducatorRosters(Request $request,$educator_id,$soft_delete=null){
        $user = $request->user();
        $educator = Educator::findOrFail($educator_id);
        $educatorClass = new EducatorRosterClass($educator);
//        $rosters = $educatorClass->myRosters();
        $rosters = $educatorClass->myRostersQuery()
            ->trashed($soft_delete)
            ->search($request->key)
            ->get();
        return ApiResponseClass::successResponse(RosterResource::collection($rosters));

    }

    public function getSchoolRosters(Request $request,$school_id,$soft_delete=null){
        $user = $request->user();
        $school = School::findOrFail($school_id);
        $schoolClass = new SchoolRosterClass($school);
//        $rosters = $schoolClass->myRosters();
        $rosters = $schoolClass->myRostersQuery()
            ->trashed($soft_delete)
            ->search($request->key)
            ->get();
        return ApiResponseClass::successResponse(RosterResource::collection($rosters));

    }

    public function softDeleteOrRestore(Request $request,$roster_id){
        DB::beginTransaction();
        $roster = Roster::withDeletedItems()
            ->findOrFail($roster_id);
        $roster->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse(new RosterResource($roster));

    }

    public function destroy(Request $request,$roster_id){
        DB::beginTransaction();
        $roster = Roster::withDeletedItems()
            ->findOrFail($roster_id);
        $roster->delete();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

}
