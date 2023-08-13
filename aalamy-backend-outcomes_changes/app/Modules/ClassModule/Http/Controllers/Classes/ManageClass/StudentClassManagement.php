<?php

namespace Modules\ClassModule\Http\Controllers\Classes\ManageClass;

use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Http\Controllers\Classes\ManageLevel\SchoolLevel;
use Modules\User\Models\Student;

class StudentClassManagement extends BaseManageClassAbstract implements ManageClassInterface
{

    protected Student $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    public function myClassesQuery():Builder
    {
        $myClassIds = ClassStudent::where('student_id',$this->student->id)
            ->active()
            ->pluck('class_id')->toArray();
        $myClassesQuery = ClassModel::query()
            ->whereIn('id',$myClassIds);
        return $myClassesQuery;
    }



}
