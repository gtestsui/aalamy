<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner;


use Carbon\Carbon;
use Modules\Event\Http\DTO\EventData;
use Modules\Event\Models\Event;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherEventOwner extends BaseEventOwnerAbstract  implements ManageEventOwnerInterface
{
    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
        $this->accountType = 'teacher';
    }

    public function getAccountObject(){
        return $this->teacher;
    }



    public function prepareEventTargetUserArray(EventData $eventData,Event $event){

        $parentUserTarget = $this->prepareParentIds($eventData->parentIds,$event,$eventData->all_parents);
        $studentUserTarget = $this->prepareStudentIds($eventData->studentIds,$event,$eventData->all_students);
        return array_merge($parentUserTarget,$studentUserTarget);

    }


    public function prepareParentIds(array $parentIds,Event $event,bool $all=false){
        $teacherClass = new StudentTeacherClass($this->teacher);
        $myStudentParents = $teacherClass->myStudentParentsAll();
        $myStudentParentIds = $myStudentParents->pluck('id')->toArray();

        if($all){
        	$parentIds = $myStudentParentIds;
        }else{
        	//get the shared ids between my studentParents and the parents in request
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
        $teacherClass = new StudentTeacherClass($this->teacher);
        $myStudentIds = $teacherClass->myStudentIds();

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
