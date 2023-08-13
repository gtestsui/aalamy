<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\SchoolRosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\SchoolClassManagement;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner\SchoolEventOwner;
use Modules\Roster\Models\Roster;
use Modules\User\Models\School;

class SchoolCalendar extends BaseCalendarAbstract implements ManageCalendarClassInterface
{
    private School $school;

    public function __construct(School $school,$date,$partOfDateName)
    {
        $this->school = $school;
        $this->setProperties($date,$partOfDateName);

    }



    /**
     * get all my rosterAssignments in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all events target me in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all my event in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyCalendarByPartOfDate(){

        $eventsTargetMe = [];

        $eventOwnerClass = new SchoolEventOwner($this->school);
        $myEvents = $eventOwnerClass->getMyEventsByPartOfDateWithRelations($this->date,$this->partOfDateName);

        $rosterAssignmentClass = new SchoolRosterAssignment($this->school);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByMyAssignmentsAndPartOfDateFromStartDate(
            $this->date,$this->partOfDateName
        );


        return [$rosterAssignments,$eventsTargetMe,$myEvents];
    }


    /**
     * get all assignments belong to $classId and in the same month or day or.. of $date depends on the $partOfDateName Variable
     * even it's not my assignments
     * get all my event
     */
    public function getMyClassCalendarByPartOfDate($classId){
        $schoolClassManage = new SchoolClassManagement($this->school);
        $myClass = $schoolClassManage->myClassesByIdOrFail($classId);
        $classInfoIds = ClassInfo::where('class_id',$myClass->id)
            ->pluck('id')->toArray();

        $rosterIds = Roster::whereIn('class_info_id',$classInfoIds)->pluck('id')->toArray();

        $rosterAssignments =RosterAssignment::whereIn('roster_id',$rosterIds)
//            ->whereMonth('start_date',$this->date->month)
            ->withAllRelations()
            ->{'by'.ucfirst($this->partOfDateName).'FromStartDate'}($this->date)//call dynamic scope (byMonthFromStartDate,byDayFromStartDate,..)
//            ->isLocked(false)
            ->isHidden(false)
            ->get();

        return $rosterAssignments;
    }

}
