<?php


namespace Modules\Notification\Http\Controllers\Classes\DiscussionCorner;


use App\Http\Controllers\Classes\ApplicationModules;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\User\Models\User;

class ApprovePostRequestNotification extends NotificationClass
{
    private $discussionCornerPost;
    private $fromUser;

    public function __construct(DiscussionCornerPost $discussionCornerPost,User $fromUser)
    {
        $this->discussionCornerPost = $discussionCornerPost;
        $this->fromUser = $fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.approve_your_post',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'your post has been approved';
        $this->notificationTitle_ar = 'تم '.
            ' قبول '.
            ' منشورك ';
        $this->notificationBody = 'your post has been approved';
        $this->notificationBody_ar = 'تم '.
            ' قبول '.
            ' منشورك ';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'discussionCornerPost' => $this->discussionCornerPost,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $toUserId = $this->discussionCornerPost->user_id;


        $this->toUserIds = [$toUserId];
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
