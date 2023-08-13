<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers;


use Modules\User\Models\Student;

class StudentMeetingTarget extends BaseMeetingTargetAbstract
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
