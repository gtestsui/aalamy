<?php


namespace Modules\User\Http\Controllers\Classes\ManageStudent;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Illuminate\Database\Eloquent\Builder;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\SchoolClassManagement;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Traits\ManageStudentParentTrait;

class StudentSchoolClass extends BaseManageStudentAbstract implements ManageStudentParentInterface
{

    use ManageStudentParentTrait;

    protected School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    public function myStudentsQuery(): ?Builder
    {

        $myStudentsQuery = SchoolStudent::query();
        $myStudentsQuery->where('school_id',$this->school->id)
            ->search(Request('key'),[],[
                'Student.User'
            ])
            ->active();
        return  $myStudentsQuery;

    }

    public function allMyStudentDoesntBelongsToClass(){
        $schoolClass = new SchoolClassManagement($this->school->id);
        $myClasses = $schoolClass->myClasses();
        $myClassesIds = $myClasses->pluck('id')->toArray();
        $students = SchoolStudent::where('school_id',$this->school->id)
            ->whereHas('Student',function ($query)use ($myClassesIds){
               return $query->whereDoesntHave('ClassStudents',function ($q)use ($myClassesIds){
                  return $q->whereNotIn('class_id',$myClassesIds);
               });
            })
            ->with('Student.User')
            ->get();
        return $students;
    }

    /**
     * we should delete the student from the school
     * and delete him just from all classes(ClassStudent table)
     * belongs to school
     * because the others table are related cascade with ClassStudent table
     *
     */
    public function deleteSchoolStudent(SchoolStudent $schoolStudent){
        $manageClass = new SchoolClassManagement($this->school);
        $myClasses = $manageClass->myClasses();

        $myClassIds = $myClasses->pluck('id');
        $classStudents = ClassStudent::whereIn('class_id',$myClassIds)
            ->where('student_id',$schoolStudent->student_id)
            ->get();

        foreach ($classStudents as $classStudent){
            $classStudent->softDeleteObject();
        }
        $schoolStudent->softDeleteObject();
        return true;


//        ClassStudent::whereIn('class_id',[11])
//            ->where('student_id',$schoolStudent->student_id)
//            ->unActivate();
//
//        /*foreach ($classStudents as $classStudent){
//            $classStudent->softDeleteObject();
//        }*/
//
//        $schoolStudent->unActivate();

    }




}
