<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventTargetedUsers;


use Carbon\Carbon;
use Modules\Event\Models\Event;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class TeacherEventTarget extends BaseEventTargetAbstract
{
    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
        $this->accountType = 'teacher';
    }

    public function getAccountObject(){
        return $this->teacher;
    }


}
