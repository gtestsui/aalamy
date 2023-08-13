<?php


namespace Modules\Notification\Http\Controllers\Classes\Meeting;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Meeting\Models\Meeting;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;
use Mail;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class NewMeetingNotification extends NotificationClass
{
    private $targetUserArray,
            $meeting;


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
                                Meeting $meeting)
    {
        $this->targetUserArray = $targetUserArray;
        $this->meeting = $meeting;


        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_meeting',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'new meeting';
        $this->notificationTitle_ar = 'تمت اضافة جلسة جديدة';
        $this->notificationBody = 'new meeting';
        $this->notificationBody_ar = 'تمت اضافة جلسة جديدة';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'meeting' => $this->meeting,
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

//        $TeacherUserIds = Teacher::whereIn('id',$teacherIds)
//            ->pluck('user_id')
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
