<?php


namespace Modules\Notification\Http\Controllers\Classes\Notifications;


use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;

class VerificationCodeNotification extends NotificationClass
{
    private $user,$code;
    public function __construct($user,$code)
    {
        $this->user =$user;
        $this->code =$code;

    }

    public function notifyFor(){

       $this->toUserIds = [$this->user->id];
       return $this->toUserIds;

    }

    public function notifyToMail(){
        $data = [
            'code' => $this->code,
            'user' => $this->user,
        ];
        Mail::send('Notification::mails.verificationCodeMail', $data, function($message){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->user->email)
                ->subject('Verification Account Code');

        });
    }
}
