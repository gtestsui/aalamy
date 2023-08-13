<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\Meeting;



use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Meeting\Http\Controllers\Classes\RunningMeetingClass;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;

abstract class BaseManageMeetingAbstract
{


    /**
     * @return Collection
     */
    abstract public function myLastCreatedMeetingsTargetMeOrImTheOwner();

    /**
     * @return Collection of Meeting
     */
    public function myRunningMeetingsTargetMeOrImTheOwner(){
        $myLastCreatedMeetings = $this->myLastCreatedMeetingsTargetMeOrImTheOwner();

        $myRunningMeetings = (new RunningMeetingClass($myLastCreatedMeetings))
            ->getMyRunningMeetings();
        return $myRunningMeetings;
    }


}
