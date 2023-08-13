<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\TeacherRosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\TeacherClassManagement;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner\TeacherEventOwner;
use Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers\TeacherEventTarget;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Teacher;

class TeacherCalendar extends BaseCalendarAbstract implements ManageCalendarClassInterface
{
    private Teacher $teacher;

    public function __construct(Teacher $teacher,$date,$partOfDateName)
    {
        $this->teacher = $teacher;
        $this->setProperties($date,$partOfDateName);

    }

    /**
     * get all my rosterAssignments belong to my rosters in same month or day or.. of $date depends on the $partOfDateName Variable
     * get all my event in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyCalendarByPartOfDate(){

        $eventOwnerClass = new TeacherEventTarget($this->teacher);
        $eventsTargetMe = $eventOwnerClass->getEventsTargetMeByPartOfDateWithRelations(
            $this->date,$this->partOfDateName
        );


        $eventOwnerClass = new TeacherEventOwner($this->teacher);
        $myEvents = $eventOwnerClass->getMyEventsByPartOfDateWithRelations(
            $this->date,$this->partOfDateName
        );

        $rosterAssignmentClass = new TeacherRosterAssignment($this->teacher);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByMyAssignmentsAndPartOfDateFromStartDate(
            $this->date,$this->partOfDateName
        );

        return [$rosterAssignments,$eventsTargetMe,$myEvents];
    }


    /**
     * get all assignments belong to $classId and in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all my event in same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyClassCalendarByPartOfDate($classId){

        $teacherClassManage = new TeacherClassManagement($this->teacher);
        $myClass = $teacherClassManage->myClassesByIdOrFail($classId);
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
