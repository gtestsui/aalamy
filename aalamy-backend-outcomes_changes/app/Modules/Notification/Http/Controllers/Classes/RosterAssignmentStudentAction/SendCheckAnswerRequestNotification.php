<?php


namespace Modules\Notification\Http\Controllers\Classes\RosterAssignmentStudentAction;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\RosterAssignment\Models\RosterAssignment;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;

class SendCheckAnswerRequestNotification extends NotificationClass
{
    public $typeNum;
    private $rosterAssignmentStudentAction;
//    public $notificationType;
    private $fromUser;
//    protected $toUserIds=[];
//    protected $userIdsWithTeacherIdsAsKeys=[];//because the teacher and educator may have the same userId

    public function __construct(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {
        $this->rosterAssignmentStudentAction = $rosterAssignmentStudentAction;
    	$rosterAssignmentStudentAction->load('Student.User');
        $fromUser = $rosterAssignmentStudentAction->Student->User;
        $this->fromUser = $fromUser;
        $this->notificationImage = $fromUser->image;
        $this->typeNum = configFromModule('panel.notification_types.check_answer_request',ApplicationModules::NOTIFICATION_MODULE_NAME);
        $this->notificationType = parent::getNotificationType($this->typeNum);

        $this->notificationTitle = 'there is a new check answer request from '.$fromUser->getFullName();
        $this->notificationTitle_ar = 'تم اضافة طلب التحقق من الاجابة جديد من قبل '.
            $fromUser->getFullName();
        $this->notificationBody = 'there is a new check answer request from '.$fromUser->getFullName();
        $this->notificationBody_ar = 'تم اضافة طلب التحقق من الاجابة جديد من قبل  '.
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
