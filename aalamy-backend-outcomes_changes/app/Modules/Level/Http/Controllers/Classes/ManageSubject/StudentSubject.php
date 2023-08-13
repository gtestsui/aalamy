<?php


namespace Modules\Level\Http\Controllers\Classes\ManageSubject;


use App\Modules\Level\Http\Controllers\Classes\ManageSubject\BaseSubjectAbstract;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Models\Subject;
use Modules\User\Models\Student;

class StudentSubject extends BaseSubjectAbstract
{

    protected Student $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
    }



    public function mySubjectsQuery(){
        $classIds = ClassStudent::where('student_id',$this->student->id)
            ->active()
            ->pluck('class_id')->toArray();
        $levelsIds = ClassModel::whereIn('id',$classIds)->pluck('level_id')->toArray();

        $mySubjectsQuery = Subject::query()
            ->whereHas('LevelSubjects',function ($query)use ($levelsIds){
                return $query->whereIn('level_id',$levelsIds);
            })
            ->with(['LevelSubjects'=>function($query)use($levelsIds){//we have loaded because its needed during the navigated
                return $query->whereIn('level_id',$levelsIds);
            }]);

        return $mySubjectsQuery;
    }






}
