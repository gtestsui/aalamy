<?php


namespace Modules\Notification\Http\Controllers\Classes\ContactUs;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\ContactUs\Models\ContactUs;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\User\Models\User;

class NewContactUsNotification extends NotificationClass
{
    private $contactUs;

    public function __construct(ContactUs $contactUs,User $fromUser)
    {

        $this->fromUser = $fromUser;
        $this->contactUs = $contactUs;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.contact_us',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'new contact us';
        $this->notificationTitle_ar = 'تمت اضافة طلب تواصل معنا';
        $this->notificationBody = 'new contact us';
        $this->notificationBody_ar = 'تمت اضافة طلب تواصل معنا';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'contactUs' => $this->contactUs,
            ],

        ];
        return  array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){
        $userIds = User::superAdmin()->pluck('id')->toArray();
        $this->toUserIds = $userIds;
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
