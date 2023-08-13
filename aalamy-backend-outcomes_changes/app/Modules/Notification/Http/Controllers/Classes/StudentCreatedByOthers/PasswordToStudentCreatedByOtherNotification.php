<?php


namespace Modules\Notification\Http\Controllers\Classes\StudentCreatedByOthers;


use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;

class PasswordToStudentCreatedByOtherNotification extends NotificationClass
{
    private $arrayOfObjectsOfEmailsAndPasswords;
    public function __construct($arrayOfObjectsOfEmailsAndPasswords)
    {
        $this->arrayOfObjectsOfEmailsAndPasswords = $arrayOfObjectsOfEmailsAndPasswords;

    }


    public function notifyToMail(){

        foreach ($this->arrayOfObjectsOfEmailsAndPasswords as $element){
            $data = [
                'password' => $element['password'],
                'email' => $element['email'],
            ];
            Mail::send('Notification::mails.StudentCreatedByOther.sendPasswordToStudentMail', $data, function($message)use ($data){

                $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
                $message->to($data['email'])
                    ->subject('Your Account Information');

            });
        }


    }
}
