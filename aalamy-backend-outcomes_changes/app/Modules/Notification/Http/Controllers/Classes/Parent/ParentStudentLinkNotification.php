<?php


namespace Modules\Notification\Http\Controllers\Classes\Parent;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;

class ParentStudentLinkNotification extends NotificationClass
{
    private $user,$student,$link,$toEmail;
    public function __construct($user,$student,$toEmail)
    {
        $this->user =$user;
        $this->student =$student;
        $this->link = configFromModule('panel.add_child_to_parent_link',ApplicationModules::USER_MODULE_NAME)/* config('User.panel.add_child_to_parent_link')*/
            .$student->parent_code;
        $this->toEmail = $toEmail;

    }

    public function notifyToMail(){
        $data = [
            'link' => $this->link,
            'user' => $this->user,
            'schoolName' => isset($this->user->School)?$this->user->School->school_name:null ,
            'studentName' => getFullNameSeperatedByDash($this->student->User->fname,$this->student->User->lname)
        ];
        Mail::send('Notification::mails.Parent.parentStudentLinkMail', $data, function($message){

            $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
            $message->to($this->toEmail)
                ->subject('Student link');

        });
    }
}
