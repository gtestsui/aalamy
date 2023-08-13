<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\Teacher;

class TeacherMeetingOwnerClass extends BaseManageMeetingByOwnerAbstract
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * @return Builder
     */
    public function myMeetingsQuery(){
        $myMeetingsQuery = Meeting::query()
            ->myOwnAsTeacher($this->teacher->id);

        return  $myMeetingsQuery;
    }


    public function prepareMeetingTargetUserArray(MeetingData $meetingData,Meeting $meeting){

        $studentTeacherClass = new StudentTeacherClass($this->teacher);
        $targetedStudentArray = $this->prepareTargetedStudenForCreate($studentTeacherClass,$meetingData->studentIds,$meeting);
        $targetedParentArray = $this->prepareTargetedParentForCreate($studentTeacherClass,$meetingData->parentIds,$meeting);

        return array_merge($targetedStudentArray,$targetedParentArray);
    }



}
