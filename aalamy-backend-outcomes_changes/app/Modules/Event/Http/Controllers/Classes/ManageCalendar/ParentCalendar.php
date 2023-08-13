<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


use Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers\ParentEventTarget;
use Modules\User\Models\ParentModel;

class ParentCalendar extends BaseCalendarAbstract
{
    private ParentModel $parent;

    public function __construct(ParentModel $parent,$date,$partOfDateName)
    {
        $this->parent = $parent;
        $this->setProperties($date,$partOfDateName);

    }

    /**
     * get all rosterAssignments belong to my rosters
     * get all event target me in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    public function getMyCalendarByPartOfDate(){

        $eventTargetClass = new ParentEventTarget($this->parent);
        $eventsTargetMe = $eventTargetClass->getEventsTargetMeByPartOfDateWithRelations($this->date,$this->partOfDateName);

        $myEvents = [];


        $rosterAssignments = [];

        return [$rosterAssignments,$eventsTargetMe,$myEvents];
    }



}
