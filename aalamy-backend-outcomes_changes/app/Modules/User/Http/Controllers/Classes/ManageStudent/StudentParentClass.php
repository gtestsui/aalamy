<?php


namespace Modules\User\Http\Controllers\Classes\ManageStudent;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;

class StudentParentClass extends BaseManageStudentAbstract
{

    protected ParentModel $parent;
    public function __construct(ParentModel $parent)
    {
        $this->parent = $parent;
    }

    public function myStudentsQuery(): ?Builder
    {

        $myStudentsQuery = ParentStudent::query();
        $myStudentsQuery->where('parent_id',$this->parent->id);
        return  $myStudentsQuery;

    }


    public function addStudentsToParent($parentCodes): array
    {
        $studentsArray = [];
        foreach ($parentCodes as $parentCode){
            $studentsArray[] = $this->addStudentToParent($parentCode);
        }

        return $studentsArray;
    }

    public function addStudentToParent($parentCode): Student
    {
        $student = $this->getStudentByParentCode($parentCode);
        $found = ParentStudent::where( 'parent_id',$this->parent->id)
            ->where('student_id',$student->id)
            ->first();
        if(is_null($found))
            ParentStudent::create([
                'parent_id' => $this->parent->id,
                'student_id' => $student->id
            ]);
        return $student;
    }

    public function getStudentByParentCode($parentCode): Student
    {
        $student = Student::where('parent_code',$parentCode)->first();
        if(is_null($student))
            throw new ErrorMsgException(transMsg('invalid_parent_code',ApplicationModules::USER_MODULE_NAME));
        return $student;
    }

}
