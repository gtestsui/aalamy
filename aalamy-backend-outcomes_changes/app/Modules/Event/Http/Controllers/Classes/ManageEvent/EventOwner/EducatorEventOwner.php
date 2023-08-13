<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner;


use Carbon\Carbon;
use Modules\Event\Http\DTO\EventData;
use Modules\Event\Models\Event;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;

class EducatorEventOwner extends BaseEventOwnerAbstract implements ManageEventOwnerInterface
{
    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
        $this->accountType = 'educator';
    }

    public function getAccountObject(){
        return $this->educator;
    }




    public function prepareEventTargetUserArray(EventData $eventData,Event $event){

        $parentUserTarget = $this->prepareParentIds($eventData->parentIds,$event,$eventData->all_parents);
        $studentUserTarget = $this->prepareStudentIds($eventData->studentIds,$event,$eventData->all_students);
        return array_merge($parentUserTarget,$studentUserTarget);

    }


    public function prepareParentIds(array $parentIds,Event $event,bool $all=false){

        $educatorClass = new StudentEducatorClass($this->educator);
        $myStudentParents = $educatorClass->myStudentParentsAll();
        $myStudentParentIds = $myStudentParents->pluck('id')->toArray();

        if($all){
        	$parentIds = $myStudentParentIds;
        }else{
        	//get the shared ids between my student and the student in request
            $parentIds = array_intersect($myStudentParentIds,$parentIds);
        }
            

        $parentUserTarget = [];
        if(count($parentIds)>0){

            

            foreach ($parentIds as $parentId){
                $parentUserTarget[] = [
                    'parent_id' => $parentId,
                    'event_id' => $event->id,
                    'student_id' => null,
                    'teacher_id' => null,
                    'created_at' => Carbon::now(),
                ];

            }
        }
        return $parentUserTarget;
    }

    public function prepareStudentIds(array $studentIds,Event $event,bool $all=false){

        $educatorClass = new StudentEducatorClass($this->educator);
        $myStudentIds = $educatorClass->myStudentIds();

        if($all){
        	$studentIds = $myStudentIds;
        }else{
        	//get the shared ids between my student and the student in request
            $studentIds = array_intersect($myStudentIds,$studentIds);
        }
            

        $studentUserTarget = [];
        if(count($studentIds)>0){

            

            foreach ($studentIds as $studentId){
                $studentUserTarget[] = [
                    'student_id' => $studentId,
                    'event_id' => $event->id,
                    'parent_id' => null,
                    'teacher_id' => null,
                    'created_at' => Carbon::now(),

                ];

            }
        }
        return $studentUserTarget;
    }

}
