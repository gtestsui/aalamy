<?php


namespace Modules\Level\Http\Controllers\Classes\ManageLesson;


use App\Modules\Level\Http\Controllers\Classes\ManageLesson\ManageLessonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Level\Http\Controllers\Classes\ManageUnit\SchoolUnit;
use Modules\Level\Models\Lesson;
use Modules\User\Models\School;
class SchoolLesson implements ManageLessonInterface
{

    protected School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function myLessonsQuery($lessonId=null): Builder
    {
        //school lessons ether add by teacher or school
        $schoolUnitClass = new SchoolUnit($this->school);
        $myUnitIds = $schoolUnitClass->myUnitsAll()
            ->pluck('id');

        $myLessonsQuery = Lesson::query();
        $myLessonsQuery->whereIn('unit_id',$myUnitIds)
            ->byNullableId($lessonId);
        return $myLessonsQuery;

    }

    public function myLessonsPaginate():LengthAwarePaginator
    {
        $myLessonsQuery = $this->myLessonsQuery();
        $myLessons = $myLessonsQuery
            ->with('Unit')
            ->paginate(config('Level.panel.lessons_paginate_num'));
        return $myLessons;
    }

    public function myLessonsPaginateWithFilter($unitId=null):LengthAwarePaginator
    {
        $myLessonsQuery = $this->myLessonsQuery();
        $myLessons = $myLessonsQuery->with(['Unit.LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            }])
            ->when(isset($unitId),function ($query)use ($unitId){
                return $query->where('unit_id',$unitId);
            })
            ->paginate(config('Level.panel.units_paginate_num'));
        return $myLessons;
    }

    /**
     * @return Collection of Lesson model
     */
    public function myLessonsAll($unitId=null):Collection
    {
        $myLessonsQuery = $this->myLessonsQuery();
        $myLessons = $myLessonsQuery->when(isset($unitId),function ($query)use ($unitId){
            return $query->where('unit_id',$unitId);
        })->get();
        return $myLessons;
    }

    public function myLessonsById($lessonId):Collection
    {
        $myLessonsQuery = $this->myLessonsQuery($lessonId);
        $myLessons = $myLessonsQuery->get();
        return $myLessons;
    }


}
