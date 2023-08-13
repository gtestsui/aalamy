<?php

namespace Modules\RosterAssignment\Http\Controllers\Classes;


use Carbon\Carbon;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentStudentPage;

class RosterAssignmentStudentPageServices{


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
    public static function addStudentPages($assignmentIds,$rosterIds,?Page $page=null){
        $arrayForCreate = [];

        if(!is_array($assignmentIds))
            $assignmentIds = [$assignmentIds];
        if(!is_array($rosterIds))
            $rosterIds = [$rosterIds];


        $rosterAssignments = RosterAssignment::whereIn('assignment_id',$assignmentIds)
            ->whereIn('roster_id',$rosterIds)
            ->with('Roster.RosterStudents.ClassStudent.Student')
//            ->with('Assignment.Pages')
            ->with(['RosterAssignmentPages'=>function($query)use($page){
                return $query->when(isset($page),function ($query)use ($page){
                    return $query->where('page_id',$page->id);
                });
            }])
            ->get();


        foreach ($rosterAssignments as $rosterAssignment){
            foreach ($rosterAssignment->Roster->RosterStudents as $rosterStudent) {
                foreach ($rosterAssignment->RosterAssignmentPages as $rosterAssignmentPage) {
                    $arrayForCreate[] = [
                        'roster_assignment_page_id' => $rosterAssignmentPage->id,
                        'student_id' => $rosterStudent->ClassStudent->Student->id,
                        'is_hidden' => $rosterAssignmentPage->is_hidden,
                        'is_locked' => $rosterAssignmentPage->is_locked,
                        'created_at' => Carbon::now(),
                    ];
                }
            }
        }

        RosterAssignmentStudentPage::insert($arrayForCreate);
    }


    public static function addDefinedStudentPages($rosterId,$studentIds){

//        if(!is_array($rosterIds))
//            $rosterIds = [$rosterIds];
        if(!is_array($studentIds))
            $studentIds = [$studentIds];


        //initialize all assignment pages in the roster to the new student
        $rosterAssignmentPages = RosterAssignmentPage::whereHas('RosterAssignment',function ($query)use ($rosterId){
                return $query->where('roster_id',$rosterId);
            })
            ->get();

        $arrayForCreate = [];
        foreach ($rosterAssignmentPages as $rosterAssignmentPage) {
            foreach ($studentIds as $studentId){
                $arrayForCreate[] = [
                    'roster_assignment_page_id' => $rosterAssignmentPage->id,
                    'student_id' => $studentId,
                    'is_hidden' => $rosterAssignmentPage->is_hidden,
                    'is_locked' => $rosterAssignmentPage->is_locked,
                    'created_at' => Carbon::now(),
                ];
            }
        }
        RosterAssignmentStudentPage::insert($arrayForCreate);
    }



}
