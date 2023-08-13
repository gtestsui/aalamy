<?php


namespace Modules\Notification\Http\Controllers\Classes\Manual;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\ManualNotification;
use Modules\Notification\Models\Notification;
use Modules\User\Models\User;

class NewManualNotification extends NotificationClass
{
    private $fromUser;
    private $arrayOfArraysOfUserIds;
    private $manualNotification;

    public function __construct(array $arrayOfArraysOfUserIds,array $userIdsWithTeacherIdsAsKeys,ManualNotification $manualNotification,User $fromUser)
    {
        $this->arrayOfArraysOfUserIds = $arrayOfArraysOfUserIds;
        $this->userIdsWithTeacherIdsAsKeys = $userIdsWithTeacherIdsAsKeys;
        $this->manualNotification = $manualNotification;

        $this->fromUser = $fromUser;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.manual_notification',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = $manualNotification->subject;
        $this->notificationTitle_ar = $manualNotification->content;
        $this->notificationBody = $manualNotification->subject;
        $this->notificationBody_ar = $manualNotification->content;
        $this->notificationImage = $fromUser->image;


    }

    public function body(){
        $data = [
            'notificationData' =>[
                'from_user' => $this->fromUser,
            ],
        ];
        return array_merge($data,$this->getNotficationBody());
    }

    public function notifyFor(){

//        return $this->toUserIds;
    }

    public function notifyToFirebase(){

        parent::toFireBaseByArrayOfArraysOfUserIds($this->arrayOfArraysOfUserIds,$this->body(),$this->notificationTitle,$this->notificationBody);
        parent::toFireBaseForProccessingTeacher($this->userIdsWithTeacherIdsAsKeys,$this->body(),$this->notificationTitle,$this->notificationBody);

    }



    public function notifyToDataBase(){
        foreach ($this->arrayOfArraysOfUserIds as $userIds){
            $chunkedUserIds = array_chunk($userIds,500);

            foreach($chunkedUserIds as $chunk){
                $arrayForCreate = [];
                foreach($chunk as $userId){
                    $arrayForCreate [] = [
                        'type_id' => $this->notificationType->id,
                        'user_id' => $userId,
                        'data'    => json_encode($this->body()),
                        'created_at' => Carbon::now()
                    ];
                }
                Notification::insert($arrayForCreate);
            }
        }

        $this->notifyUsersAsTeachersToDataBase();


        return true;
    }


    public function notifyToMail($toUser=null){
        $data = [
            'subject' =>$this->manualNotification->subject,
            'content' =>$this->manualNotification->content ,
            'fromUser' =>$this->fromUser ,
        ];
        $finalUserIds = array_values($this->userIdsWithTeacherIdsAsKeys);
        foreach ($this->arrayOfArraysOfUserIds as $userIds){
            $finalUserIds = array_merge($finalUserIds,$userIds);
        }
        $users = User::whereIn('id',$finalUserIds)->get();
        foreach ($users as $user){
            Mail::send('Notification::mails.manualNotificationMail', $data, function($message) use($user){

                $message->from(env('MAIL_USERNAME','classkits@gmail.com'));
                $message->to($user->email)
                    ->subject('notification from '.$this->fromUser->getFullName());

            });
        }


    }


}
