<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers\StudentEventTarget;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\StudentRosterClass;
use Modules\User\Models\Student;

class StudentCalendar extends BaseCalendarAbstract implements ManageCalendarClassInterface
{
    private Student $student;

    public function __construct(Student $student,$date,$partOfDateName)
    {
        $this->student = $student;
        $this->setProperties($date,$partOfDateName);

    }

    /**
     * get all rosterAssignments belong to my rosters
     * get all event target me in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyCalendarByPartOfDate(){

        $eventTargetClass = new StudentEventTarget($this->student);
        $eventsTargetMe = $eventTargetClass->getEventsTargetMeByPartOfDateWithRelations($this->date,$this->partOfDateName);

        $myEvents = [];


        $rosterAssignmentClass = new StudentRosterAssignment($this->student);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByMyRostersAndPartOfDateFromStartDate(
            $this->date,$this->partOfDateName
        );

        return [$rosterAssignments,$eventsTargetMe,$myEvents];
    }

    /**
     * get all assignments belong to $classId and the myRosters in the class
     * in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyClassCalendarByPartOfDate($classId){


        $schoolRosterManage = new StudentRosterClass($this->student);
        $myRosters = $schoolRosterManage->allMyRostersByClassId($classId);
        $myRosterIds = $myRosters->pluck('id')->toArray();

        $rosterAssignments =RosterAssignment::whereIn('roster_id',$myRosterIds)
//            ->whereMonth('start_date',$this->date->month)
            ->withAllRelations()
            ->{'by'.ucfirst($this->partOfDateName).'FromStartDate'}($this->date)//call dynamic scope (byMonthFromStartDate,byDayFromStartDate,..)
//            ->isLocked(false)
            ->isHidden(false)
            ->get();

        return $rosterAssignments;
    }

}
