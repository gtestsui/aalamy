<?php

namespace Modules\RosterAssignment\Http\Controllers\Classes;


use Carbon\Carbon;
use Modules\Assignment\Models\Assignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\RosterStudent;
use Modules\User\Models\Student;

class AssignmentStudentPageServices{


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
    public static function addStudentPages($assignmentIds,$rosterIds){
        $arrayForCreate = [];

        if(!is_array($assignmentIds))
            $assignmentIds = [$assignmentIds];
        if(!is_array($rosterIds))
            $rosterIds = [$rosterIds];

//        $rosterStudents = RosterStudent::whereIn('roster_id',$rosterIds)->get();
        $classStudentIds = RosterStudent::whereIn('roster_id',$rosterIds)->pluck('class_student_id')->toArray();
        $studentIds = ClassStudent::whereIn('id',$classStudentIds)->pluck('student_id')->toArray();
        $students = Student::whereIn('id',$studentIds)->get();
        $assignments = Assignment::whereIn('id',$assignmentIds)
            ->with('Pages')->get();

        foreach ($assignments as $assignment){
            foreach ($assignment->Pages as $page){
                foreach ($students as $student){
                    $arrayForCreate[] = [
                        'page_id' => $page->id,
                        'student_id' => $student->id,
                        'created_at' => Carbon::now(),
                    ];
                }
//                foreach ($rosterStudents as $rosterStudent){
//                    $arrayForCreate[] = [
//                        'page_id' => $page->id,
//                        'roster_student_id' => $rosterStudent->id,
//                        'created_at' => Carbon::now(),
//                    ];
//                }
            }
        }

        RosterAssignmentStudentPage::insert($arrayForCreate);
    }



}
