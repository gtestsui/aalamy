<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers;


use Modules\User\Models\Teacher;

class TeacherMeetingTarget extends BaseMeetingTargetAbstract
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
