<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;

class EducatorMeetingOwnerClass extends BaseManageMeetingByOwnerAbstract
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    public function myMeetingsQuery(){

        $myMeetingsQuery = Meeting::query()
            ->myOwnAsEducator($this->educator->id);

        return  $myMeetingsQuery;

    }



    public function prepareMeetingTargetUserArray(MeetingData $meetingData,Meeting $meeting){

        $studentEducatorClass = new StudentEducatorClass($this->educator);

        $targetedStudentArray = $this->prepareTargetedStudenForCreate($studentEducatorClass,$meetingData->studentIds,$meeting);

        $targetedParentArray = $this->prepareTargetedParentForCreate($studentEducatorClass,$meetingData->parentIds,$meeting);

        return array_merge($targetedParentArray,$targetedStudentArray);
    }

    public function checkMeetingBelongsToMyOwn(Meeting $meeting){
        if($meeting->educator_id == $this->educator->id)
            return true;
        return false;
    }


}
