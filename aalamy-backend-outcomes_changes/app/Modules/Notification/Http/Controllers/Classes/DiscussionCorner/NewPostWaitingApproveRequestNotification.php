<?php


namespace Modules\Notification\Http\Controllers\Classes\DiscussionCorner;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class NewPostWaitingApproveRequestNotification extends NotificationClass
{
    private $discussionCornerPost;
    private $fromUser;

    public function __construct(DiscussionCornerPost $discussionCornerPost,User $fromUser)
    {
        $this->discussionCornerPost = $discussionCornerPost;
        $this->fromUser = $fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_post_waiting_approve',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = "there is a new post waiting your approve from ".$fromUser->getFullName();
        $this->notificationTitle_ar = 'تمت اضافة منشور جديد ينتظر موافقتك من قبل '.
            $fromUser->getFullName();
        $this->notificationBody = 'there is a new post waiting your approve from '.$fromUser->getFullName();
        $this->notificationBody_ar = 'تمت اضافة منشور جديد ينتظر موافقتك من قبل '.
            $fromUser->getFullName();

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

        if(!is_null($this->discussionCornerPost->educator_id)){
            $toUserId = Educator::findOrFail($this->discussionCornerPost->educator_id)->user_id;
        }else{
            $toUserId = School::findOrFail($this->discussionCornerPost->school_id)->user_id;

        }


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
