<?php


namespace Modules\Event\Http\Controllers\Classes\ManageEvent\EventOwner;


use Carbon\Carbon;
use Modules\Event\Http\DTO\EventData;
use Modules\Event\Models\Event;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolEventOwner extends BaseEventOwnerAbstract  implements ManageEventOwnerInterface
{
    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
        $this->accountType = 'school';
    }

    public function getAccountObject(){
        return $this->school;
    }


    public function prepareEventTargetUserArray(EventData $eventData,Event $event){

        $parentUserTarget = $this->prepareParentIds($eventData->parentIds,$event,$eventData->all_parents);
        $studentUserTarget = $this->prepareStudentIds($eventData->studentIds,$event,$eventData->all_students);
        $teacherUserTarget = $this->prepareTeacherIds($eventData->teacherIds,$event,$eventData->all_teachers);
        return array_merge($parentUserTarget,$studentUserTarget,$teacherUserTarget);

    }



    public function prepareParentIds(array $parentIds,Event $event,bool $all=false){
        $schoolClass = new StudentSchoolClass($this->school);
        $myStudentParents = $schoolClass->myStudentParentsAll();
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

        $schoolClass = new StudentSchoolClass($this->school);
        $myStudentIds = $schoolClass->myStudentIds();

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

    public function prepareTeacherIds(array $teacherIds,Event $event,bool $all=false){
        $myTeacherIds = Teacher::where('school_id',$this->school->id)
            ->pluck('id')->toArray();
        if($all){
        	$teacherIds = $myTeacherIds;
        }else{
        	//get the shared ids between my student and the student in request
            $teacherIds = array_intersect($myTeacherIds,$teacherIds);
        }
            

        $teacherUserTarget = [];
        if(count($teacherIds)>0){


            

            foreach ($teacherIds as $teacherId){
                $teacherUserTarget[] = [
                    'student_id' => null,
                    'event_id' => $event->id,
                    'parent_id' => null,
                    'teacher_id' => $teacherId,
                    'created_at' => Carbon::now(),

                ];

            }
        }
        return $teacherUserTarget;
    }

}
