<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Meeting;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\TeacherMeetingOwnerClass;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers\TeacherMeetingTarget;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\Teacher;

class TeacherMeetingClass extends BaseManageMeetingAbstract
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Collection
     */
    public function myLastCreatedMeetingsTargetMeOrImTheOwner(){


        $meetingClass = new TeacherMeetingOwnerClass($this->teacher);
        $myOwnLastCreatedMeetings = $meetingClass->getMyLastCreatedMeetingsAll();

        $meetingClass = new TeacherMeetingTarget($this->teacher);
        $myLastCreatedMeetingsTargetMe = $meetingClass->getAllLastCreatedMeetingsTargetMe();


        $lastCreatedMeetingsTargetMeOrImTheOwner = $myOwnLastCreatedMeetings->merge($myLastCreatedMeetingsTargetMe);

        return  $lastCreatedMeetingsTargetMeOrImTheOwner;

    }




}
