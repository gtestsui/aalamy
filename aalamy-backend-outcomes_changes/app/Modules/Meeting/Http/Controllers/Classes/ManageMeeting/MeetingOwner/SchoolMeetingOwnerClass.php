<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolMeetingOwnerClass extends BaseManageMeetingByOwnerAbstract
{

    private School $school;
    private array $teacherUserIdsWithTeacherIdAsKeys;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * @return Builder
     */
    public function myMeetingsQuery(){
        $teacherIds = Teacher::where('school_id',$this->school->id)->pluck('id')->toArray();
        $myMeetingsQuery = Meeting::query()
            ->myOwnAsSchool($teacherIds,$this->school->id);

        return  $myMeetingsQuery;
    }

    public function prepareMeetingTargetUserArray(MeetingData $meetingData,Meeting $meeting){
        $studentSchoolClass = new StudentSchoolClass($this->school);
        $targetedStudentArray = $this->prepareTargetedStudenForCreate($studentSchoolClass,$meetingData->studentIds,$meeting);
        $targetedParentArray = $this->prepareTargetedParentForCreate($studentSchoolClass,$meetingData->parentIds,$meeting);
        $targetedTeacherArray = $this->prepareTargetedTeacherForCreate($meetingData->teacherIds,$meeting);

        return array_merge($targetedStudentArray,$targetedParentArray,$targetedTeacherArray);
    }


    protected function prepareTargetedTeacherForCreate(array $targetedTeacherArray,Meeting $meeting){
        $myTeacherIds = Teacher::where('school_id',$this->school->id)
            ->whereIn('id',$targetedTeacherArray)
            ->pluck('id')
            ->toArray();
//        $teacherIds = array_intersect($myTeacherIds,$targetedTeacherArray);
        $teacherArrayForCreate = $this->prepareArrayForCreate(
            $myTeacherIds,
            $meeting,
            'teacher_id'
        );

        return $teacherArrayForCreate;
    }


}
