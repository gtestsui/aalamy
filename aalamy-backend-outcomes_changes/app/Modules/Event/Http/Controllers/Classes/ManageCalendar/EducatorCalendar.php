<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\EducatorRosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner\EducatorEventOwner;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Educator;

class EducatorCalendar extends BaseCalendarAbstract implements ManageCalendarClassInterface
{

    private Educator $educator;

    public function __construct(Educator $educator,$date,$partOfDateName)
    {
        $this->educator = $educator;
        $this->setProperties($date,$partOfDateName);
    }



    /**
     * @param string $date as date format Y-m-d
     * @param string $partOfDateName enum of (month,day,..) will use this param to get
     * the calendar compatible with date depends on $partOfDateName
     * get all my rosterAssignments in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all events target me in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all my event in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyCalendarByPartOfDate(){

        $eventsTargetMe = [];

        $eventOwnerClass = new EducatorEventOwner($this->educator);
        $myEvents = $eventOwnerClass->getMyEventsByPartOfDateWithRelations($this->date,$this->partOfDateName);

        $rosterAssignmentClass = new EducatorRosterAssignment($this->educator);
        $rosterAssignments = $rosterAssignmentClass->myRosterAssignmentsByMyAssignmentsAndPartOfDateFromStartDate(
            $this->date,$this->partOfDateName
        );


        return [$rosterAssignments,$eventsTargetMe,$myEvents];
    }


    /**
     * get all assignments belong to $classId and in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyClassCalendarByPartOfDate($classId){


        $educatorClassManage = new EducatorClassManagement($this->educator);
        $myClass = $educatorClassManage->myClassesByIdOrFail($classId);
        $classInfoIds = ClassInfo::where('class_id',$myClass->id)
            ->pluck('id')->toArray();
        $rosterIds = Roster::whereIn('class_info_id',$classInfoIds)->pluck('id')->toArray();

        $rosterAssignments =RosterAssignment::whereIn('roster_id',$rosterIds)
//            ->whereMonth('start_date',$this->date->month)
            ->withAllRelations()
            ->{'by'.ucfirst($this->partOfDateName).'FromStartDate'}($this->date)//call dynamic scope (byMonthFromStartDate,byDayFromStartDate,..)
            ->get();

        return $rosterAssignments;
    }

}
