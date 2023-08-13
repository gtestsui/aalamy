<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Meeting;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\EducatorMeetingOwnerClass;
use Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner\MeetingManagementFactory;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;

class EducatorMeetingClass extends BaseManageMeetingAbstract
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Collection
     */
    public function myLastCreatedMeetingsTargetMeOrImTheOwner(){


        $meetingClass = new EducatorMeetingOwnerClass($this->educator);
        $myLastCreatedMeetings = $meetingClass->getMyLastCreatedMeetingsAll();


        return  $myLastCreatedMeetings;

    }



}
