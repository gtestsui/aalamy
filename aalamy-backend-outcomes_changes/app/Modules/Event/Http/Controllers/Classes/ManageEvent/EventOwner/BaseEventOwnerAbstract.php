<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Event\Models\Event;

abstract class BaseEventOwnerAbstract
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
    protected function getMyEventsByPartOfDateQuery(Carbon $date,$partOfDateName){
        $myEventsByPartOfDateQuery = Event::query()
            ->belongsToMe($this->getAccountType(),$this->getAccountObject())
//            ->whereMonth('date',$date->month)
            ->{'by'.ucfirst($partOfDateName)}($date)//call dynamic scope (byMonth,byDay,..)
            ->orderBy('date','asc');
        return $myEventsByPartOfDateQuery;
    }

    /**
     * @return Collection of Event model
     *
     */
    public function getMyEventsByPartOfDate($date,$partOfDateName){
        $myEventsByPartOfDate = $this->getMyEventsByPartOfDateQuery($date,$partOfDateName)
            ->get();
        return $myEventsByPartOfDate;
    }

    /**
     * @return Collection of Event model
     *
     */
    public function getMyEventsByPartOfDateWithRelations($date,$partOfDateName){
        $myEventsByPartOfDate = $this->getMyEventsByPartOfDateQuery($date,$partOfDateName)
            ->withAllRelations()
            ->get();
        return $myEventsByPartOfDate;
    }





}
