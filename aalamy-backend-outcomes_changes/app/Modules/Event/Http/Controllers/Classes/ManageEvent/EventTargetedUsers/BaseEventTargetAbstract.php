<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Event\Models\Event;

abstract class BaseEventTargetAbstract
{

    /**
     * the account type of concrete class
     * @var string
     */
    protected $accountType;

    public abstract function getAccountObject();

    public function getAccountType(){
        return $this->accountType;
    }

    /**
     * @param Carbon $date
     * @param string $partOfDateName
     * @return Builder
     */
    protected function getEventsTargetMeByPartOfDateQuery(Carbon $date,$partOfDateName){
        $eventsTargertMeByPartOfDateQuery = Event::query()
            ->isTargeteMe($this->getAccountType(),$this->getAccountObject())
//            ->whereMonth('date',$date->month)
            ->{'by'.ucfirst($partOfDateName)}($date)//call dynamic scope (byMonth,byDay,..)
            ->orderBy('date','asc');
        return $eventsTargertMeByPartOfDateQuery;
    }

    /**
     * @return Collection of Event model
     *
     */
    public function getEventsTargetMeByPartOfDate($date,$partOfDateName){
        $eventsTargertMeByPartOfDate = $this->getEventsTargetMeByPartOfDateQuery($date,$partOfDateName)
            ->get();
        return $eventsTargertMeByPartOfDate;
    }

    /**
     * @return Collection of Event model
     *
     */
    public function getEventsTargetMeByPartOfDateWithRelations($date,$partOfDateName){
        $eventsTargertMeByPartOfDate = $this->getEventsTargetMeByPartOfDateQuery($date,$partOfDateName)
            ->withAllRelations()
            ->get();
        return $eventsTargertMeByPartOfDate;
    }





}
