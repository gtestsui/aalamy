<?php


namespace App\Modules\Level\Http\Controllers\Classes\ManageUnit;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Level\Models\Subject;
use Modules\Level\Models\Unit;

abstract class BaseUnitAbstract
{


    /**
     * @return Builder
     */
    abstract public function myUnitsQuery($unitId=null);


    public function myUnitsPaginate():LengthAwarePaginator
    {
        $myUnitsQuery = $this->myUnitsQuery();
        $myUnits = $myUnitsQuery->withLevelSubjectInfo()
            ->paginate(config('Level.panel.units_paginate_num'));
        return $myUnits;
    }


    public function myUnitsPaginateWithFilter($levelSubjectId=null):LengthAwarePaginator
    {
        $myUnitsQuery = $this->myUnitsQuery();
        $myUnits = $myUnitsQuery->withLevelSubjectInfo()
            ->when(isset($levelSubjectId),function ($query)use ($levelSubjectId){
                return $query->where('level_subject_id',$levelSubjectId);
            })
            ->paginate(config('Level.panel.units_paginate_num'));
        return $myUnits;
    }


    /**
     * @return Collection of Unit model
     */
    public function myUnitsAll($levelSubjectId=null):Collection
    {
        $myUnitsQuery = $this->myUnitsQuery();
        $myUnits = $myUnitsQuery->when(isset($levelSubjectId),function ($query)use ($levelSubjectId){
            return $query->where('level_subject_id',$levelSubjectId);
        })->get();
        return $myUnits;
    }

    /**
     * @return Collection of Unit model
     */
    public function myUnitsAllWithLevelSubject($levelSubjectId=null):Collection
    {
        $myUnitsQuery = $this->myUnitsQuery();
        $myUnits = $myUnitsQuery->when(isset($levelSubjectId),function ($query)use ($levelSubjectId){
            return $query->where('level_subject_id',$levelSubjectId);
        })
            ->with('LevelSubject.Level','LevelSubject.Subject')
            ->get();
        return $myUnits;
    }


    /**
     * @return Unit|null|Builder of Unit model
     */
    public function myUnitsById(?int $unitId)
    {
        $myUnitsQuery = $this->myUnitsQuery($unitId);
        $myUnits = $myUnitsQuery->first();
        return $myUnits;
    }

}
