<?php


namespace Modules\Notification\Http\Controllers\Classes\SchoolRequest;


use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\SchoolInvitation\Models\SchoolTeacherInvitation;

class SchoolTeacherInvitationNotification extends NotificationClass
{
    private $user,$schoolTeacherInvitation;

    public function __construct($user,SchoolTeacherInvitation $schoolTeacherInvitation,$introductoryMessage)
    {
        $this->user =$user;
        $this->schoolTeacherInvitation =$schoolTeacherInvitation;
        $this->introductoryMessage =$introductoryMessage;

    }



    public function notifyToMail($toUser=null){
            $this->user->load('School');

        $data = [
            'schoolName' => $this->user->School->name,
            'schoolEmail' => $this->user->email,
            'link' => $this->schoolTeacherInvitation->link,
            'introductoryMessage' => $this->introductoryMessage,
        ];
        Mail::send('Notification::mails.SchoolRequest.schoolTeacherInvitationMail', $data, function($message) use($toUser){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->schoolTeacherInvitation->teacher_email)
                ->subject('School Invitation');

        });
    }
}
