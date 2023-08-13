<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


interface ManageCalendarClassInterface
{

    /**
     * get all rosterAssignments belong to $classId
     * in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyClassCalendarByPartOfDate($classId);


}
