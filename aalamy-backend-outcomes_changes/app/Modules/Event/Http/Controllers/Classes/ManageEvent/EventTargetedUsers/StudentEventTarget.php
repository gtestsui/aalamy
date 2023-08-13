<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers;


use Carbon\Carbon;
use Modules\Event\Models\Event;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\Student;

class StudentEventTarget extends BaseEventTargetAbstract
{
    private Student $student;
    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->accountType = 'student';
    }

    public function getAccountObject(){
        return $this->student;
    }


}
