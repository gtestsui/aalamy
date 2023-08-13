<?php


namespace Modules\Level\Http\Controllers\Classes\ManageLesson;


use App\Modules\Level\Http\Controllers\Classes\ManageLesson\ManageLessonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Level\Models\Lesson;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionServices;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherLesson implements ManageLessonInterface
{

    protected Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }


    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function myLessonsQuery($lessonId=null): Builder
    {
        $havePermission = PermissionServices::isHaveOneOfThisPermissions($this->teacher,[
            'lesson' => ['create','update','delete'],
        ]);

        if($havePermission){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolLessonClass = new SchoolLesson($school);
            return  $schoolLessonClass->myLessonsQuery();
        }else{
            $myLessonsQuery = Lesson::query();
            $myLessonsQuery->where('user_id',$this->teacher->user_id)
                ->byNullableId($lessonId)
                ->where('type','teacher');
            return $myLessonsQuery;
        }


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
