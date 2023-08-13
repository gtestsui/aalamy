<?php


namespace Modules\Notification\Http\Controllers\Classes\SchoolRequest;

use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;

class SchoolTeacherRequestNotification extends NotificationClass
{
    public $typeNum = 1;
    private $toUser,$fromUser,$teacherRequest;

    public function __construct(SchoolTeacherRequest $teacherRequest)
    {

        $this->teacherRequest =$teacherRequest;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.school_teacher_request',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'Joining school request';
        $this->notificationTitle_ar = 'طلب انضمام للمدرسة';
        $this->notificationBody = 'Joining school request';
        $this->notificationBody_ar = 'طلب انضمام للمدرسة';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'teacherRequest' => $this->teacherRequest,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());


    }

    public function notifyFor(){

        $this->toUser = $this->teacherRequest->{ucfirst($this->teacherRequest->to)}->User;
        $this->fromUser = $this->teacherRequest->{ucfirst($this->teacherRequest->from)}->User;


        $this->toUserIds = [$this->toUser->id];
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
        $this->{'notifyToMailFrom'.ucfirst($this->teacherRequest->from)}();

    }

    private function notifyToMailFromSchool(){
            $this->fromUser->load('School');
        $data = [
            'schoolName' => $this->fromUser->School->name,
            'schoolEmail' => $this->fromUser->email,
        ];
        Mail::send('Notification::mails.SchoolRequest.schoolTeacherRequestFromSchoolMail', $data, function($message){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->toUser->email)
                ->subject('School Invitation');

        });
    }

    private function notifyToMailFromEducator(){
        $data = [
            'educatorName' => getFullNameSeperatedByDash($this->fromUser->fname,$this->fromUser->lname) ,
            'educatorEmail' => $this->fromUser->email,
        ];
        Mail::send('Notification::mails.SchoolRequest.schoolTeacherRequestFromEducatorMail', $data, function($message){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->toUser->email)
                ->subject('School Invitation');

        });
    }
}
