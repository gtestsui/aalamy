<?php


namespace App\Modules\Level\Http\Controllers\Classes\ManageLevel;



use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Level\Models\Level;

abstract class BaseLevelAbstract
{


    /**
     * @var ?array-key $filterByFields
     * the key is field name in my fillable model and the value its its value i want to filter about
     */
    protected ?array $filterByFields=[];

    abstract  public function myLevelsQuery();

    abstract  public function myLevelSubjectsQuery();



    /**
     * @return Collection|Level
     */
    public function myLevels(){

        $levels = $this->myLevelsQuery()->get();
        return $levels;
    }

    /**
     * @return Level|null|Builder
     */
    public function myLevelsById($id){

        $level = $this->myLevelsQuery()->where('id',$id)->first();
        return $level;
    }

    /**
     * @return Level|Builder
     * @throws ModelNotFoundException
     */
    public function myLevelsByIdOrFail($id){

        $level = $this->myLevelsQuery()->where('id',$id)->firstOrFail();
        return $level;
    }



    /**
     * @param  ?array-key $filterByFields
     * the key is field name in my fillable model and the value its its value i want to filter about
     */
    public function myLevelSubjectsPaginate(array $filterByFields=[]){
        $this->filterByFields = $filterByFields;
        $myLevelSubjectsQuery = $this->myLevelSubjectsQuery();
        $myLevelSubjects = $myLevelSubjectsQuery
            ->with(['Level','Subject'])
            ->paginate(config('Level.panel.level_subject_paginate_num'));
        return $myLevelSubjects;
    }

    public function myLevelSubjectById($id){
        return $this->myLevelSubjectsQuery()
                ->where('id',$id)
                ->first();
    }

    public function myLevelSubjectsAll(){
        $myLevelSubjectsQuery = $this->myLevelSubjectsQuery();
        $myLevelSubjects = $myLevelSubjectsQuery
            ->with(['Level','Subject'])
            ->get();
        return $myLevelSubjects;
    }

    public function myLevelSubjectsByLevelId($levelId){
        $myLevelSubjects = $this->myLevelSubjectsQuery()
            ->with(['Level','Subject'])
            ->where('level_id',$levelId)
            ->get();
        return $myLevelSubjects;
    }

}
