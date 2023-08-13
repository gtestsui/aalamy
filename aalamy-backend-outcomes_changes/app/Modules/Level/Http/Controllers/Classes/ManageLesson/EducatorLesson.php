<?php


namespace Modules\Level\Http\Controllers\Classes\ManageLesson;


use App\Modules\Level\Http\Controllers\Classes\ManageLesson\ManageLessonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Level\Models\Lesson;
use Modules\User\Models\Educator;

class EducatorLesson implements ManageLessonInterface
{

    protected Educator $educator;
    protected $teacherId;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }


    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function myLessonsQuery($lessonId=null): Builder
    {
        $lessonsQuery = Lesson::query();
        $lessonsQuery->where('user_id',$this->educator->user_id)
            ->byNullableId($lessonId)
            ->where('type','educator');
        return $lessonsQuery;
    }

    /**
     * @return LengthAwarePaginator of Lesson model
     */
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

    /**
     * @return Collection of Lesson model
     */
    public function myLessonsById(?int $lessonId):Collection
    {
        $myLessonsQuery = $this->myLessonsQuery($lessonId);
        $myLessons = $myLessonsQuery->get();
        return $myLessons;
    }



}
