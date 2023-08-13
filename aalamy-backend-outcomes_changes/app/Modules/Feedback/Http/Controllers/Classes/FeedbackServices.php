<?php

namespace Modules\Feedback\Http\Controllers\Classes;



use App\Exceptions\ErrorMsgException;
use Modules\Feedback\Http\Controllers\Classes\ManageFeedback\ManageFeedbackAboutStudentInterface;
use Modules\RosterAssignment\Http\Controllers\Classes\Attendance\StudentAttendanceClass;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentAttendanceData;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class FeedbackServices
{


    public static function getTargetRosterAssignments($user,$from_date,$to_date,?array $roster_assignment_ids){

        if(isset($from_date)){
            $filterRosterAssignmentAttendanceData = FilterRosterAssignmentAttendanceData::fromArray([
                'start_date' => $from_date,
                'end_date' => $to_date,
            ]);

            $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
            $myRosterAssignmentIds = $rosterAssignmentClass
                ->setFilter($filterRosterAssignmentAttendanceData->filter_roster_assignment_data)
                ->myRosterAssignmentsIdsByMyRosters();

            return $myRosterAssignmentIds;

        }elseif(isset($roster_assignment_ids)&&count($roster_assignment_ids)){
            return $roster_assignment_ids;
        }else{
            return [];
        }

//        throw new ErrorMsgException(
//            'some thing wrong happen while initialize roster assignment ids'
//        );

    }


}
