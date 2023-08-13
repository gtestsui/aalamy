<?php

namespace Modules\RosterAssignment\Http\Controllers\Classes;


use Carbon\Carbon;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAttendance;

class RosterAssignmentStudentAttendanceServices{


    /**
     * @note we have 3 statuses
     * first one if we are trying to link one assignment to one roster
     * second one if we are trying to link one assignment to many rosters
     * third one if we are trying to link many assignments to one roster
     *
     * in first state the $assignments variable will be collection have one item of Assignment Model
     * because the param $assignmentIds will be one element(int)  => so that the foreach on it have cost (1)
     * in second state the $assignments variable will be collection have one item of Assignment Model
     * because the param $assignmentIds will be one element(int) => so that the foreach on it have cost (1)
     * in third state the $assignments variable will be collection have many items of Assignment Model => so that the foreach on it have cost (n)
     */
    public static function initializeRosterAssignmentAttendance($assignmentIds,$rosterIds){
        $arrayForCreate = [];

        if(!is_array($assignmentIds))
            $assignmentIds = [$assignmentIds];
        if(!is_array($rosterIds))
            $rosterIds = [$rosterIds];

        $rosterAssignments = RosterAssignment::whereIn('assignment_id',$assignmentIds)
            ->whereIn('roster_id',$rosterIds)
            ->with('Roster.RosterStudents.ClassStudent.Student')
            ->get();


        foreach ($rosterAssignments as $rosterAssignment){
            foreach ($rosterAssignment->Roster->RosterStudents as $rosterStudent){
                $arrayForCreate[] = [
                    'student_id' => $rosterStudent->ClassStudent->Student->id,
                    'roster_assignment_id' => $rosterAssignment->id,
                    'attendee_status' => false,
                    'created_at' => Carbon::now(),
                ];
            }

        }

        RosterAssignmentStudentAttendance::insert($arrayForCreate);
    }


    /**
     * @param array|int|mixed $studentIds
     * @param int|mixed $rosterId
     */
    public static function addStudentsAtendancesByRoster($rosterId,$studentIds){

        if(!is_array($studentIds))
            $studentIds = [$studentIds];

        $rosterAssignments = RosterAssignment::where('roster_id',$rosterId)->get();

        $arrayForCreate = [];
        foreach ($rosterAssignments as $rosterAssignment){
            foreach ($studentIds as $studentId){
                $arrayForCreate[] = [
                    'roster_assignment_id' => $rosterAssignment->id,
                    'student_id' => $studentId,
                    'created_at' => Carbon::now(),
                ];
            }
        }

        RosterAssignmentStudentAttendance::insert($arrayForCreate);

    }



}
