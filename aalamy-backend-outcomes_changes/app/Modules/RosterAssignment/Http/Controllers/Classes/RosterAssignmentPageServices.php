<?php

namespace Modules\RosterAssignment\Http\Controllers\Classes;


use Carbon\Carbon;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentPage;

class RosterAssignmentPageServices{


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
    public static function addRosterAssignmentPages($assignmentIds,$rosterIds){
        $arrayForCreate = [];

        if(!is_array($assignmentIds))
            $assignmentIds = [$assignmentIds];
        if(!is_array($rosterIds))
            $rosterIds = [$rosterIds];

        $rosterAssignments = RosterAssignment::whereIn('assignment_id',$assignmentIds)
            ->whereIn('roster_id',$rosterIds)
            ->with('Assignment.Pages')
            ->get();


        foreach ($rosterAssignments as $rosterAssignment){
            foreach ($rosterAssignment->Assignment->Pages as $page){
                $arrayForCreate[] = [
                    'page_id' => $page->id,
                    'roster_assignment_id' => $rosterAssignment->id,
                    'is_hidden' => $page->is_hidden,
                    'is_locked' => $page->is_locked,
                    'created_at' => Carbon::now(),
                ];
            }

        }

        RosterAssignmentPage::insert($arrayForCreate);
    }

    public static function addEmptyPageToRosterAssignmentsContainsThisAssignment(Page $page,$assignmentId){
        $arrayForCreate = [];

        //all rosterAssignments contain this assignment
        $rosterAssignmentsIds = RosterAssignment::where('assignment_id',$assignmentId)
            ->pluck('id')
            ->toArray();

        foreach ($rosterAssignmentsIds as $rosterAssignmentId){
            $arrayForCreate[] = [
                'page_id' => $page->id,
                'roster_assignment_id' => $rosterAssignmentId,
                'is_hidden' => $page->is_hidden,
                'is_locked' => $page->is_locked,
                'created_at' => Carbon::now(),
            ];

        }

        RosterAssignmentPage::insert($arrayForCreate);
    }



}
