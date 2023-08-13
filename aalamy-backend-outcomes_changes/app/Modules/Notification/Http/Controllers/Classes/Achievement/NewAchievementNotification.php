<?php


namespace Modules\Notification\Http\Controllers\Classes\Achievement;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;

class NewAchievementNotification extends NotificationClass
{
    private $fromUser,
            $classIds,//ids of the classes that contain the student inside it
            $achievement;

    public function __construct(array $classIds,
                                StudentAchievement $achievement)
    {
        $this->classIds = $classIds;
        $this->achievement = $achievement/*->load('Student.User')*/;

        $student = Student::with('User')->find($achievement->student_id);
        $this->notificationImage = $student->User->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_achievement',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'new achievement ';
        $this->notificationTitle_ar = 'تمت اضافة انجاز جديد ';
        $this->notificationBody = 'new achievement ';
        $this->notificationBody_ar = 'تمت اضافة انجاز جديد ';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'achievement' => $this->achievement,
            ],
        ];

        return array_merge($data,$this->getNotficationBody());
    }

    public function notifyFor(){

        $targetStudentIds = ClassStudent::whereIn('class_id',$this->classIds)
            ->pluck('student_id')->toArray();
        $targetUserIds = Student::whereIn('id',$targetStudentIds)
            ->pluck('user_id')->toArray();

        $targetParentsUserIds = ParentModel::whereHas('ParentStudents',function ($query){
                return $query->where('student_id',$this->achievement->student_id);
            })
            ->where('user_id','!=',$this->achievement->user_id)
            ->pluck('user_id')
            ->toArray();

        $this->toUserIds = array_merge($targetUserIds,$targetParentsUserIds);
        return $this->toUserIds;
    }

    public function notifyToFirebase(){

        parent::toFireBase($this->toUserIds,$this->body(),$this->notificationTitle,$this->notificationBody);
    }



    public function notifyToDataBase(){

        $this->notifyUsersToDataBase();

        return true;
    }


    public function notifyToMail($toUser=null){

    }


}
