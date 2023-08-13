<?php


namespace Modules\Meeting\Http\Controllers\Classes\ManageMeeting\MeetingOwner;



use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Meeting\Http\Controllers\Classes\RunningMeetingClass;
use Modules\Meeting\Http\DTO\MeetingData;
use Modules\Meeting\Models\Meeting;

abstract class BaseManageMeetingByOwnerAbstract
{

    protected array $parentUserIds =[];
    protected array $studentUserIds=[];

    public abstract function myMeetingsQuery();
    /**
     * @param MeetingData $meetingData
     * @param Meeting $meeting
     * @return array
     */
    public abstract function prepareMeetingTargetUserArray(MeetingData $meetingData,Meeting $meeting);


    /**
     * @return LengthAwarePaginator
     */
    public function getMyOwnMeetingsPaginate($classId=null,$searchKey=''){
        $myMeetings = $this->myMeetingsQuery()
            ->when(isset($classId),function ($query)use ($classId){
                return $query->where('class_id',$classId);
            })
            ->search($searchKey)
            ->paginate(10);
        return $myMeetings;
    }


    /**
     * @return LengthAwarePaginator
     */
    public function getMyOwnMeetingsAll(){
        $myMeetings = $this->myMeetingsQuery()
            ->get();
        return $myMeetings;
    }


    /**
     * the running meeting from 2 days ago until now because
     * should not exist running meetings before some hours from now
     * @return Collection
     */
    public function getMyLastCreatedMeetingsAll(){
        $myMeetings = $this->myMeetingsQuery()
            ->whereDate('date_time','>=',Carbon::now()->subDays(2))
            ->get();
        return $myMeetings;
    }



    /**
     * @return Collection of Meeting
     */
    public function myOwnRunningMeetings(){
        $myLastCreatedMeetings = $this->getMyLastCreatedMeetingsAll();

        $myRunningMeetings = (new RunningMeetingClass($myLastCreatedMeetings))
            ->getMyRunningMeetings();
        return $myRunningMeetings;
    }

    /**
     * @param $id
     * @return Meeting|null
     */
    public function getMyMeetingById($id){
        $meeting = $this->myMeetingsQuery()->where('id',$id)->first();
        return $meeting;
    }

    /**
     * @param $id
     * @throws ModelNotFoundException
     * @return Meeting
     */
    public function getMyMeetingByIdOrFail($id){
        $meeting = $this->myMeetingsQuery()->where('id',$id)->firstOrFail();
        return $meeting;
    }

    protected function prepareTargetedStudenForCreate(BaseManageStudentAbstract $studentEducatorClass,array $targetedStudentIds,Meeting $meeting){
//        $myStudentIds =  $studentEducatorClass->myStudentIds();
        $myStudentIds =  $studentEducatorClass->myStudentsQuery()
            ->whereIn('student_id',$targetedStudentIds)
            ->pluck('student_id')
            ->toArray();

        //get the shared ids between my parentStudent and the parentStudent in request
        $studentIds = array_intersect($myStudentIds,$targetedStudentIds);
        $studentArrayForCreate = $this->prepareArrayForCreate($studentIds,$meeting,'student_id');

        return $studentArrayForCreate;

    }

    protected function prepareTargetedParentForCreate(ManageStudentParentInterface $studentEducatorClass,array $targetedStudentParentIds,Meeting $meeting){
//        $myStudentParents = $studentEducatorClass->myStudentParentsAll();
        $studentParentIds = $studentEducatorClass->myStudentParentsQuery()
            ->whereIn('id',$targetedStudentParentIds)
            ->pluck('id')
            ->toArray();

//        $myStudentParentIds = $myStudentParents->pluck('id')->toArray();
//        $studentParentIds = array_intersect($myStudentParentIds,$targetedStudentParentIds);
        $studentArrayForCreate = $this->prepareArrayForCreate($studentParentIds,$meeting,'parent_id');

        return $studentArrayForCreate;

    }

    protected function prepareArrayForCreate(array $ids,Meeting $meeting,$columnName){
        $arrayForCreate = [];
        if(count($ids)>0){
            foreach ($ids as $id){
                $row = [
                        'meeting_id' => $meeting->id,
                        'parent_id' => null,
                        'student_id' => null,
                        'teacher_id' => null,
                        'created_at' => Carbon::now(),
                    ];
                $row[$columnName] = $id;
                $arrayForCreate[] = $row;
            }
        }
        return $arrayForCreate;
    }




}
