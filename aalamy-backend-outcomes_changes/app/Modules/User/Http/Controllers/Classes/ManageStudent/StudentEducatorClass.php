<?php


namespace Modules\User\Http\Controllers\Classes\ManageStudent;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Illuminate\Database\Eloquent\Builder;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Traits\ManageStudentParentTrait;

class StudentEducatorClass extends BaseManageStudentAbstract implements ManageStudentParentInterface
{


    use ManageStudentParentTrait;

    protected Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }


    public function myStudentsQuery(): ?Builder
    {

        $myStudentsQuery = EducatorStudent::query();
        $myStudentsQuery->where('educator_id',$this->educator->id)
            ->active();
        return  $myStudentsQuery;

    }


    /**
     * we should delete the student from the educator
     * and delete him just from all classes(ClassStudent table)
     * belongs to educator
     * because the other table are related cascade with ClassStudent table
     *
     */
    public function deleteSchoolStudent(EducatorStudent $educatorStudent){
        $manageClass = new EducatorClassManagement($this->educator);
        $myClasses = $manageClass->myClasses();

//        $educatorLevel = new EducatorLevel($this->educator);
//        $myClasses = $educatorLevel-myClasses();
        $myClassIds = $myClasses->pluck('class_id');
        ClassStudent::whereIn('class_id',$myClassIds)
            ->where('student_id',$educatorStudent->student_id)
            ->delete();
        $educatorStudent->delete();
        return true;
    }



}
