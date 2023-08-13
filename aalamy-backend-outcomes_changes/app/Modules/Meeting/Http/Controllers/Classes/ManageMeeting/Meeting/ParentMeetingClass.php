<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Meeting;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\TeacherMeetingOwnerClass;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers\ParentMeetingTarget;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers\StudentMeetingTarget;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingTargetedUsers\TeacherMeetingTarget;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class ParentMeetingClass extends BaseManageMeetingAbstract
{

    private ParentModel $parent;
    public function __construct(ParentModel $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection
     */
    public function myLastCreatedMeetingsTargetMeOrImTheOwner(){


        $meetingClass = new ParentMeetingTarget($this->parent);
        $myLastCreatedMeetingsTargetMe = $meetingClass->getAllLastCreatedMeetingsTargetMe();

        return  $myLastCreatedMeetingsTargetMe;

    }


}
