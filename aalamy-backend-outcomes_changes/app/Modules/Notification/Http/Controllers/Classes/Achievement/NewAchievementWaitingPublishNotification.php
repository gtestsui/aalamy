<?php


namespace Modules\Notification\Http\Controllers\Classes\Achievement;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\StudentAchievement\Models\StudentAchievement;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\ParentModel;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

class NewAchievementWaitingPublishNotification extends NotificationClass
{
    private $fromUser,
            $achievement;

    public function __construct(StudentAchievement $achievement)
    {
        $this->achievement = $achievement;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_achievement_waiting_publish',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $student = Student::with('User')->find($achievement->student_id);
        $this->notificationImage = $student->User->image;

        $this->notificationTitle = 'new achievement waiting your approval to publish';
        $this->notificationTitle_ar = 'تمت اضافة انجاز جديد بانتظار موافقتك';
        $this->notificationBody = 'new achievement waiting your approval to publish';
        $this->notificationBody_ar = 'تمت اضافة انجاز جديد بانتظار موافقتك';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'achievement' => $this->achievement,
            ],

        ];
        return  array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        //here get my student school id
        $schoolStudent = SchoolStudent::where('student_id',$this->achievement->student_id)
            ->active()->first();

        //here get my student educators ids
        $educatorIds = EducatorStudent::where('student_id',$this->achievement->student_id)
            ->active()->pluck('educator_id')->toArray();

        //here get my student teacher ids
        $teacherIds = ClassStudent::where('student_id',$this->achievement->student_id)
            ->active()->pluck('teacher_id')->toArray();



        $schoolUserId = $schoolStudent->School->user_id;
        $educatorUserIds = Educator::whereIn('id',$educatorIds)->pluck('user_id')->toArray();
        $this->toUserIds = array_merge($educatorUserIds,[$schoolUserId]);
        $this->userIdsWithTeacherIdsAsKeys  = Teacher::whereIn('id',$teacherIds)
            ->pluck('user_id','id')
            ->toArray();

//        $teachers  = Teacher::whereIn('id',$teacherIds)->get();
//
//        foreach ($teachers as $teacher){
//            $this->userIdsWithTeacherIdsAsKeys[] = [
//                $teacher->id => $teacher->user_id
//            ];
//        }


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
