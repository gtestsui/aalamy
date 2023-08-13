<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Meeting;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\SchoolMeetingOwnerClass;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolMeetingClass extends BaseManageMeetingAbstract
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * @return Collection
     */
    public function myLastCreatedMeetingsTargetMeOrImTheOwner(){


        $meetingClass = new SchoolMeetingOwnerClass($this->school);
        $myLastCreatedMeetings = $meetingClass->getMyLastCreatedMeetingsAll();


        return  $myLastCreatedMeetings;

    }


}
