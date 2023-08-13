<?php


namespace Modules\Notification\Http\Controllers\Classes\SchoolRequest;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\SchoolInvitation\Models\SchoolStudentRequest;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;

class SchoolStudentRequestNotification extends NotificationClass
{
    public $typeNum = 1;
    private $toUser,$fromUser,$studentRequest;

    public function __construct(SchoolStudentRequest $studentRequest)
    {

        $this->studentRequest =$studentRequest;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.school_student_request',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'Joining school request';
        $this->notificationTitle_ar = 'طلب انضمام للمدرسة';
        $this->notificationBody = 'Joining school request';
        $this->notificationBody_ar = 'طلب انضمام للمدرسة';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'studentRequest' => $this->studentRequest,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $this->toUser = $this->studentRequest->{ucfirst($this->studentRequest->to)}->User;
        $this->fromUser = $this->studentRequest->{ucfirst($this->studentRequest->from)}->User;


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
        $this->{'notifyToMailFrom'.ucfirst($this->studentRequest->from)}();

    }

    private function notifyToMailFromSchool(){
            $this->fromUser->load('School');

        $data = [
            'schoolName' => $this->fromUser->School->name,
            'schoolEmail' => $this->fromUser->email,
        ];
        Mail::send('Notification::mails.SchoolRequest.schoolStudentRequestFromSchoolMail', $data, function($message){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->toUser->email)
                ->subject('School Invitation');

        });
    }

    private function notifyToMailFromStudent(){
        $data = [
            'studentName' => getFullNameSeperatedByDash($this->fromUser->fname,$this->fromUser->lname) ,
            'studentEmail' => $this->fromUser->email,
        ];
        Mail::send('Notification::mails.SchoolRequest.schoolStudentRequestFromStudentMail', $data, function($message){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->toUser->email)
                ->subject('School Invitation');

        });
    }
}
