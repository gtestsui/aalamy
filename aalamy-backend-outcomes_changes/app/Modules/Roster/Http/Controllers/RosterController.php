<?php

namespace Modules\Roster\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ClassModule\Models\ClassInfo;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageUnit\UnitManagementFactory;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Http\DTO\RosterData;
use Modules\Roster\Http\Requests\Roster\CloseOrUnCloseRosterRequest;
use Modules\Roster\Http\Requests\Roster\DestroyRosterRequest;
use Modules\Roster\Http\Requests\Roster\GetMyRostersDoesntLinkeToDefinedAssignmentRequest;
use Modules\Roster\Http\Requests\Roster\GetMyRostersRequest;
use Modules\Roster\Http\Requests\Roster\StoreRosterForEducatorRequest;
use Modules\Roster\Http\Requests\Roster\StoreRosterRequest;
use Modules\Roster\Http\Requests\Roster\UpdateRosterRequest;
use Modules\Roster\Http\Resources\RosterResource;
use Modules\Roster\Models\Roster;
use Modules\User\Http\Controllers\Classes\UserServices;

class RosterController extends Controller
{

    public function myRosters(GetMyRostersRequest $request){
        $user = $request->user();
//        $manageRosterClass = RosterServices::createManageRosterClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageRosterClass = RosterManagementFactory::create($user,$request->my_teacher_id);
        $myRosters = $manageRosterClass
            ->myRostersQuery()
            ->with(['CreatedByTeacher'])
            ->get();
        return ApiResponseClass::successResponse(RosterResource::CustomCollection($myRosters,$user->account_type));
    }

    public function myRostersByLevelSubject(Request $request){
        $user = $request->user();
        $manageRosterClass = RosterManagementFactory::create($user,$request->my_teacher_id);
        $myRosters = $manageRosterClass->myRostersByLevelSubjectId($request->level_subject_id);
        return ApiResponseClass::successResponse(RosterResource::CustomCollection($myRosters,$user->account_type));
    }


    public function myRostersDoesntLinkedToDefinedAssignment(GetMyRostersDoesntLinkeToDefinedAssignmentRequest $request,$assignment_id){
        $user = $request->user();
        $manageRosterClass = RosterManagementFactory::create($user,$request->my_teacher_id);
        $myRosters = $manageRosterClass->myRostersDoesntLinkedToAssignment($assignment_id);
        return ApiResponseClass::successResponse(RosterResource::collection($myRosters));
    }


    public function myRosterByIdWithRosterAssignment(Request $request,$id){
        $user = $request->user();

        $manageRosterClass = RosterManagementFactory::create($user,$request->my_teacher_id);
        $myRoster = $manageRosterClass->myRosterByIdWithRosterAssignment($id);
        return ApiResponseClass::successResponse(new RosterResource($myRoster));
    }


    public function myRostersGroupedByOwners(Request $request){
        $user = $request->user();
//        $manageRosterClass = RosterServices::createManageRosterClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageRosterClass = RosterManagementFactory::create($user,$request->my_teacher_id);
        $myRosters = $manageRosterClass->myRostersGroupedBy();
        return $myRosters;
        return ApiResponseClass::successResponse($myRosters);

    }

    public function myRostersByClassId(GetMyRostersRequest $request,$class_id){
        $user = $request->user();
//        $manageRosterClass = RosterServices::createManageRosterClassByType($user->account_type,$user,$request->my_teacher_id);
        $manageRosterClass = RosterManagementFactory::create($user,$request->my_teacher_id);
        $myRosters = $manageRosterClass->allMyRostersByClassId($class_id);
        return ApiResponseClass::successResponse(RosterResource::CustomCollection($myRosters,$user->account_type));
    }

    public function store(StoreRosterRequest $request){
        $user = $request->user();
        $rosterData = RosterData::fromRequest($request);
        $roster = Roster::create($rosterData->all());
        return ApiResponseClass::successResponse(new RosterResource($roster,$user->account_type));
    }

    //to decrease the steps for client while creating the rosters
    //and this just for educator because for school there is a teacher_id its important
    public function storeForEducator(StoreRosterForEducatorRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        list(,$educator) = UserServices::getAccountTypeAndObject($user);
        $levelSubject = $request->getLevelSubject();
        $classInfo = ClassInfo::where('educator_id',$educator->id)
            ->where('level_subject_id',$levelSubject->id)
            ->first();

        if(is_null($classInfo)){
            $class = ClassModel::where('level_id',$levelSubject->level_id)->first();
            if(is_null($class)){
                $class = ClassModel::create([
                    'level_id' => $levelSubject->level_id,
                    'name' => $request->name,
                ]);
            }

            $classInfo = ClassInfo::create([
               'class_id' => $class->id,
               'educator_id' => $educator->id,
               'level_subject_id' => $levelSubject->id,
            ]);
        }

        $roster = Roster::create([
            'class_info_id' => $classInfo->id,
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
            'code' => RosterServices::generateRosterCode(),
        ]);

        DB::commit();
        return ApiResponseClass::successResponse(new RosterResource($roster,$user->account_type));
    }

    public function update(UpdateRosterRequest $request,$id){
        $user = $request->user();
        $rosterData = RosterData::fromRequest($request,true);
        $roster = $request->getRoster();
        $roster->update($rosterData->initializeForUpdate($rosterData));
        return ApiResponseClass::successResponse(new RosterResource($roster,$user->account_type));
    }

    public function closeOrUnClose(CloseOrUnCloseRosterRequest $request){
        $user = $request->user();
        $roster = $request->getRoster();
        $roster->closeOrUnClose();
        return ApiResponseClass::successResponse(new RosterResource($roster));

    }

    public function softDelete(DestroyRosterRequest $request,$id){
        DB::beginTransaction();
        $roster = $request->getRoster();
        $roster->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();
    }

    public function destroy(DestroyRosterRequest $request,$id){
        $roster = $request->getRoster();
        $roster->delete();
        return ApiResponseClass::deletedResponse();
    }

}
