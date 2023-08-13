<?php


namespace Modules\Notification\Http\Controllers\Classes\RosterAssignmentStudentAction;


use Carbon\Carbon;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\Roster\Models\Roster;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;
use Modules\User\Models\User;

class SendHelpRequestNotification extends NotificationClass
{
    public $typeNum;
//    public $notificationType;
    private $rosterAssignmentStudentAction;
//    private $toUserIds = [];
    private $fromUser;
//    private $userIdsWithTeacherIdsAsKeys=[];//because the teacher and educator may have the same userId


    public function __construct(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {
        $this->rosterAssignmentStudentAction = $rosterAssignmentStudentAction;
    	$rosterAssignmentStudentAction->load('Student.User');
        $fromUser = $rosterAssignmentStudentAction->Student->User;
        $this->fromUser = $fromUser;
        $this->notificationImage = $fromUser->image;
        $this->typeNum = config('Notification.panel.notification_types.help_request');
        $this->notificationType = parent::getNotificationType($this->typeNum);

        $this->notificationTitle = 'there is a new help request from '.$fromUser->getFullName();
        $this->notificationTitle_ar = 'تم اضافة طلب مساعدة جديد من قبل '.
            $fromUser->getFullName();
        $this->notificationBody = 'there is a new help request from '.$fromUser->getFullName();
        $this->notificationBody_ar = 'تم اضافة طلب مساعدة جديد من قبل '.
            $fromUser->getFullName();

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'rosterAssignmentStudentAction' => $this->rosterAssignmentStudentAction,
            ],
        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $rosterAssignmentId = $this->rosterAssignmentStudentAction->roster_assignment_id;
        $rosterAssignment = RosterAssignment::with([
                'Roster.ClassInfo'=>function($query){
                    return $query->with(['Educator','Teacher']);
                }
            ])
            ->findOrFail($rosterAssignmentId);
        $classInfo = $rosterAssignment->Roster->ClassInfo;
        if(!is_null($classInfo->Educator)){
            $this->toUserIds = [$classInfo->Educator->user_id];

        }elseif(!is_null($classInfo->Teacher)){
            $this->userIdsWithTeacherIdsAsKeys = [$classInfo->Teacher->id =>$classInfo->Teacher->user_id];

        }
        return $this->toUserIds;
    }

    public function notifyToFirebase(){

        parent::toFireBase($this->toUserIds,$this->body(),$this->notificationTitle,$this->notificationBody);
        parent::toFireBaseForProccessingTeacher(
            $this->userIdsWithTeacherIdsAsKeys,$this->body(),$this->notificationTitle,$this->notificationBody
        );

    }

    public function notifyToDataBase(){

        $this->notifyUsersToDataBase();
        $this->notifyUsersAsTeachersToDataBase();

        /*$chunkedUserIds = array_chunk($this->toUserIds,500);

        foreach($chunkedUserIds as $chunk){
            $arrayForCreate = [];
            foreach($chunk as $userId){
                $arrayForCreate [] = [
                    'type_id' => $this->notificationType->id,
                    'user_id' => $userId,
                    'data'    => json_encode($this->body()),
                    'created_at' => Carbon::now()
                ];
            }
            Notification::insert($arrayForCreate);
        }


        if(count($this->userIdsWithTeacherIdsAsKeys)){
            $arrayForCreate = [];
            $data = $this->body();
            foreach ($this->userIdsWithTeacherIdsAsKeys as $teacherId=>$userId){
                $data['teacherId'] = $teacherId;
                $arrayForCreate [] = [
                    'type_id' => $this->notificationType->id,
                    'user_id' => $userId,
                    'data'    => json_encode($data),
                    'created_at' => Carbon::now()
                ];
            }
            Notification::insert($arrayForCreate);
        }
        return true;*/
    }

}
