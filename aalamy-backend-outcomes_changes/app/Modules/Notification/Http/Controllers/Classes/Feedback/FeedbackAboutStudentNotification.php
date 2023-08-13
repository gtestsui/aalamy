<?php


namespace Modules\Notification\Http\Controllers\Classes\Feedback;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\User\Models\ParentModel;

class FeedbackAboutStudentNotification extends NotificationClass
{
    private $feedbackAboutStudent;
    public function __construct(FeedbackAboutStudent $feedbackAboutStudent)
    {

        $this->feedbackAboutStudent =$feedbackAboutStudent;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.send_feedback_about_student',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'send feedback about student';
        $this->notificationTitle_ar = 'ارسال التقييم عن الطالب';
        $this->notificationBody = 'send feedback about student';
        $this->notificationBody_ar = 'ارسال التقييم عن الطالب';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'feedbackAboutStudent' => $this->feedbackAboutStudent,
            ],
        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $parentUserIds = ParentModel::whereHas('ParentStudents',function ($query){
            return $query->where('student_id',$this->feedbackAboutStudent->student_id);
        })->pluck('user_id')->toArray();

        $this->toUserIds = $parentUserIds;
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
