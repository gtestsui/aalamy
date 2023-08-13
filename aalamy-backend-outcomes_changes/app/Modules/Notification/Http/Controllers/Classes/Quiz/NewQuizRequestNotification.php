<?php


namespace Modules\Notification\Http\Controllers\Classes\Quiz;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\Quiz\Models\Quiz;
use Modules\Roster\Models\RosterStudent;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class NewQuizRequestNotification extends NotificationClass
{
    public  $typeNum = 3;
    private $quiz;
    private $fromUser;

    public function __construct(Quiz $quiz,User $fromUser)
    {
        $this->quiz = $quiz;
        $this->fromUser = $fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_quiz',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = "there is a new quiz from ".$fromUser->getFullName();
        $this->notificationTitle_ar = 'تمت اضافة اختبار من قبل '.
            $fromUser->getFullName();
        $this->notificationBody = 'there is a new quiz from '.$fromUser->getFullName();
        $this->notificationBody_ar = ' تمت اضافة اختبار من قبل '.
            $fromUser->getFullName();

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'quiz' => $this->quiz,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){



        //We Can Do (this is better solution)

        $classStudentQuery = RosterStudent::where('roster_id',$this->quiz->roster_id)
            ->whereColumn('roster_students.class_student_id','class_students.id')
            ->getQuery();
        $studentQuery = ClassStudent::addWhereExistsQuery($classStudentQuery)
            ->whereColumn('class_students.student_id','students.id')
            ->getQuery();
        $toUserIds = Student::addWhereExistsQuery($studentQuery)->pluck('user_id')->toArray();


        //Or We Can Do

//        $classStudentIds = RosterStudent::where('roster_id',$this->quiz->roster_id)->pluck('class_student_id')->toArray();
//        $studentIds = ClassStudent::whereIn('id',$classStudentIds)->pluck('student_id')->toArray();
//        $toUserIds = Student::whereIn('id',$studentIds)->pluck('user_id')->toArray();


        //Or We Can Do
//        Student::whereHas('ClassStudents',function ($query){
//            return $query->whereHas('RosterStudents',function ($query){
//                return $query->where('roster_id',1);
//            });
//        })->pluck('user_id');


        $this->toUserIds = $toUserIds;
        return $this->toUserIds;
    }

    public function notifyToFirebase(){

        parent::toFireBase($this->toUserIds,$this->body(),$this->notificationTitle,$this->notificationBody);
    }

    public function notifyToDataBase(){
        $this->notifyUsersToDataBase();

        return true;
    }

}
