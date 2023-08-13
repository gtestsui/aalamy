<?php


namespace Modules\Event\Http\Controllers\Classes\ManageCalendar;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;

abstract class BaseCalendarAbstract
{

    /**
     * @var Carbon
     */
    protected  $date;
    /**
     * @var string enum of eventConfig.panel.filter_by_date_values
     */
    protected  $partOfDateName;



    /**
     * @param string $date
     * @return array have 2 collections
     * get all my rosterAssignments in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all events target me in the same month or day or.. of $date depends on the $partOfDateName Variable
     * get all my event in the same month or day or.. of $date depends on the $partOfDateName Variable
     */
    abstract public function getMyCalendarByPartOfDate();

//    /**
//     * get all rosterAssignments belong to $classId
//     * in the same month or day or.. of $date depends on the $partOfDateName Variable
//     */
//    abstract public function getMyClassCalendarByPartOfDate($classId);


    public function setProperties($date,$partOfDateName){
        $this->setDate($date);
        $this->setFilterDateBy($partOfDateName);
        return $this;
    }

    public function setDate($date){
        $this->date = new Carbon($date);
//        $this->date =  Carbon::createFromFormat(
//            config('panel.date_format'),$date
//        );
    }

    public function setFilterDateBy($partOfDateName){
        $this->partOfDateName = $partOfDateName;
    }

}
