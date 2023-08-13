<?php

namespace Modules\Assignment\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use Carbon\Carbon;
use Modules\Assignment\Http\DTO\AssignmentData;
use Modules\RosterAssignment\Http\DTO\RosterAssignmentData;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class AssignmentServices
{

    public static function isOwnerAssignment(Assignment $assignment,User $user,$teacherId=null){
        if(isset($teacherId)){
            if($assignment->teacher_id != $teacherId)
                return false;
        }else{
            if($assignment->{$user->account_type.'_id'} != $user->{ucfirst($user->account_type)}->id)
                return false;
        }
        return true;
    }

    public static function checkOwnerAssignment(Assignment $assignment,User $user,$teacherId=null){
        if(!Self::isOwnerAssignment($assignment, $user,$teacherId)){
                throw new ErrorUnAuthorizationException();
        }
    }

    public static function checkAddAssignmentAuthorization($levelSubjectId,User $user,$teacherId=null){

        LevelServices::checkUseLevelSubjectAuthorization($levelSubjectId,$user,$teacherId);
    }

    public static function checkUpdateAssignmentAuthorization(Assignment $assignment,User $user,$teacherId=null){

        Self::checkOwnerAssignment($assignment,$user,$teacherId);
    }

    public static function checkDeleteAssignmentAuthorization(Assignment $assignment,User $user,$teacherId=null){

        Self::checkOwnerAssignment($assignment,$user,$teacherId);
    }

    public static function checkDisplayAssignmentAuthorization(Assignment $assignment,User $user,$teacherId=null){
        if(Self::isOwnerAssignment($assignment, $user,$teacherId))
            return true;
        if($assignment->checkIsHidden())
            throw new ErrorMsgException('this assignment its locked now');
        //here we should check if the user is student then check if he belongs to same roster
        //else he school admin then check if this assignment belongs to his school
        //else he teacher check if he has permission
        Self::checkOwnerAssignment($assignment,$user,$teacherId);
    }

    public static function checkUseAssignmentAuthorization($assignment,User $user,$teacherId=null){
        Self::checkOwnerAssignment($assignment,$user,$teacherId);
    }


    public static function checkAddRosterAssignmentAuthorization(Assignment $assignment,User $user,$teacherId=null){

        Self::checkOwnerAssignment($assignment,$user,$teacherId);
    }


    /**
     * @return array
     */
    public static function prepareAssignmentRostersArrayForCreate(RosterAssignmentData $rosterAssignmentData,User $user,$teacherId=null){
        //we have used the eager load to reduce query num while we check on rosterAuthorization
        //and we have used whereDoesntHave condition to ensure that assignment_id and roster_id are unique
        $rosters = Roster::whereIn('id',$rosterAssignmentData->roster_ids)
            ->whereDoesntHave('RosterAssignments',function ($query)use ($rosterAssignmentData){
                $query->where('assignment_id',$rosterAssignmentData->assignment_id);
            })
            ->with('ClassInfo')->get();
        $arrayForCreate = [];
        foreach($rosters as $roster){
            RosterServices::checkUseRosterAuthorization($roster,$user,$teacherId);
            $arrayForCreate[] =array_merge(self::getSettingsFromRosterAssignmentData($rosterAssignmentData),[
                'assignment_id'  => $rosterAssignmentData->assignment_id,
                'roster_id'  => $roster->id,
            ]);
        }
        return $arrayForCreate;
    }


    /**
     * @return array
     */
    public static function prepareRosterAssignmentsArrayForCreate(RosterAssignmentData $rosterAssignmentData,User $user,$teacherId=null){
        //we have used whereDoesntHave condition to ensure that assignment_id and roster_id are unique
        $assignments = Assignment::whereIn('id',$rosterAssignmentData->assignment_ids)
            ->whereDoesntHave('RosterAssignments',function ($query)use ($rosterAssignmentData){
                $query->where('roster_id',$rosterAssignmentData->roster_id);
            })
            ->get();
        $arrayForCreate = [];
        foreach($assignments as $assignment){
            AssignmentServices::checkAddRosterAssignmentAuthorization($assignment,$user,$teacherId);
            $arrayForCreate[] = array_merge(self::getSettingsFromRosterAssignmentData($rosterAssignmentData),[
                'assignment_id'  => $assignment->id,
                'roster_id'  => $rosterAssignmentData->roster_id,
            ]);
        }
        return $arrayForCreate;
    }

    /**
     * @return array
     */
    public static function getSettingsFromRosterAssignmentData(RosterAssignmentData $rosterAssignmentData){
        return [
            'is_locked'  => $rosterAssignmentData->is_locked,
            'is_hidden'  => $rosterAssignmentData->is_hidden,
            'prevent_request_help'  => $rosterAssignmentData->prevent_request_help,
            'display_mark'  => $rosterAssignmentData->display_mark,
            'is_auto_saved'  => $rosterAssignmentData->is_auto_saved,
            'prevent_moved_between_pages'  => $rosterAssignmentData->prevent_moved_between_pages,
            'is_shuffling'  => $rosterAssignmentData->is_shuffling,

            'start_date'  => $rosterAssignmentData->start_date,
            'expiration_date'  => $rosterAssignmentData->expiration_date,
            'created_at'  => Carbon::now(),
        ];
    }

    /**
     * when update assignment settings then we make that update applied on all rosterAssignments related
     */
    public static function updateAllRelatedRosterAssignmentsSettings($assignmentId,AssignmentData $assignmentData){
        RosterAssignment::where('assignment_id',$assignmentId)
            ->update($assignmentData->allSettings());

    }


    /**
     * @return User
     */
    public static function getAssignmentOwner(Assignment $assignment){
        $userOfAssignmentOwner = null;
        if(!is_null($assignment->school_id)){
            if(!is_null($assignment->school_id)){
                $userOfAssignmentOwner = $assignment->load('School.User')->School->User;
            }
            if(!is_null($assignment->teacher_id)){
                $userOfAssignmentOwner = $assignment->load('Teacher.User')->Teacher->User;
            }
        }else{
            $userOfAssignmentOwner = $assignment->load('Educator.User')->Educator->User;

        }
        return $userOfAssignmentOwner;
    }
}
