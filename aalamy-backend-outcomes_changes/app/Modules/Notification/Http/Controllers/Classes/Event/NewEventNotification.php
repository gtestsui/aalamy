<?php


namespace Modules\Notification\Http\Controllers\Classes\Event;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class NewEventNotification extends NotificationClass
{
    private $targetUserArray,
            $event;
/**
 * @param array $targetUserArray array of arrays as  =[
 *                                                       [
                                                            'parent_id' => $parentId,
                                                            'event_id' => $event->id,
                                                            'student_id' => null,
                                                            'teacher_id' => null,
                                                            'created_at' => Carbon::now(),
                                                         ]
 *                                                    ]
 */
    public function __construct(array $targetUserArray,
                                Event $event,
                                User $fromUser)
    {
        $this->targetUserArray = $targetUserArray;
        $this->event = $event;
        $this->notificationImage = $fromUser->image;


        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_event',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'new event added';
        $this->notificationTitle_ar = 'تمت اضافة حدث جديد';
        $this->notificationBody = 'new event added';
        $this->notificationBody_ar = 'تمت اضافة حدث جديد';

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

        $targetUserCollections = collect($this->targetUserArray);
        $studentIds = $targetUserCollections->pluck('student_id');
        $parentIds = $targetUserCollections->pluck('parent_id');
        $teacherIds = $targetUserCollections->pluck('teacher_id');

        $studentUserIds = Student::whereIn('id',$studentIds)
            ->pluck('user_id')
            ->toArray();
        $ParentUserIds = ParentModel::whereIn('id',$parentIds)
            ->pluck('user_id')
            ->toArray();
//        $TeacherIds = Teacher::whereIn('id',$teacherIds)
//            ->pluck('id')
//            ->toArray();



        $this->userIdsWithTeacherIdsAsKeys  = Teacher::whereIn('id',$teacherIds)
            ->pluck('user_id','id')
            ->toArray();

//        foreach ($teachers as $teacher){
//            $this->userIdsWithTeacherIdsAsKeys[] = [
//                $teacher->id => $teacher->user_id
//            ];
//        }

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
