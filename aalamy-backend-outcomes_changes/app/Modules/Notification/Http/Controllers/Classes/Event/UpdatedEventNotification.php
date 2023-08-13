<?php


namespace Modules\Notification\Http\Controllers\Classes\Event;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\Event\Models\Event;
use Modules\Event\Models\EventTargetUser;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class UpdatedEventNotification extends NotificationClass
{
    private $event;

    public function __construct(Event $event)
    {

        $this->event = $event;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.updated_event',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'there is an event target you has been updated';
        $this->notificationTitle_ar = 'تم تعديل حدث قد يهمك ';
        $this->notificationBody = 'there is an event target you has been updated';
        $this->notificationBody_ar = 'تم تعديل حدث قد يهمك';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'event' => $this->event,
            ],
        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $targetUserCollections = EventTargetUser::where('event_id',$this->event->id)->get();
        $studentIds = $targetUserCollections->pluck('student_id');
        $parentIds = $targetUserCollections->pluck('parent_id');
        $teacherIds = $targetUserCollections->pluck('teacher_id');

        $studentUserIds = Student::whereIn('id',$studentIds)
            ->pluck('user_id')
            ->toArray();
        $ParentUserIds = ParentModel::whereIn('id',$parentIds)
            ->pluck('user_id')
            ->toArray();
//
//        $TeacherIds = Teacher::whereIn('id',$teacherIds)
//            ->pluck('id')
//            ->toArray();



        $this->userIdsWithTeacherIdsAsKeys  = Teacher::whereIn('id',$teacherIds)
            ->pluck('user_id','id')
            ->toArray();//array of teacher id as key and user id as value

        $userIds = array_merge($studentUserIds,$ParentUserIds);

        $this->toUserIds = $userIds;
        return $this->toUserIds;
    }

    public function notifyToFirebase(){

        parent::toFireBase($this->toUserIds,$this->body(),$this->notificationTitle,$this->notificationBody);
        parent::toFireBaseForProccessingTeacher($this->userIdsWithTeacherIdsAsKeys,$this->body(),$this->notificationTitle,$this->notificationBody);

    }



    public function notifyToDataBase(){
        $this->notifyUsersToDataBase();
        $this->notifyUsersAsTeachersToDataBase();
        return true;
    }


    public function notifyToMail($toUser=null){

    }


}
