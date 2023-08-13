<?php


namespace Modules\Notification\Http\Controllers\Classes\DiscussionCorner;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\User\Models\User;

class ApproveSurveyRequestNotification extends NotificationClass
{
    private $discussionCornerSurvey;
    private $fromUser;

    public function __construct(DiscussionCornerSurvey $discussionCornerSurvey,User $fromUser)
    {
        $this->discussionCornerSurvey = $discussionCornerSurvey;
        $this->fromUser = $fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.approve_your_survey',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'your survey has been approved';
        $this->notificationTitle_ar = 'تم '.
            'قبول'.
            'استبيانك ';
        $this->notificationBody = 'your survey has been approved';
        $this->notificationBody_ar = 'تم '.
            'قبول'.
            'استبيانك ';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'discussionCornerSurvey' => $this->discussionCornerSurvey,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $toUserId = $this->discussionCornerSurvey->user_id;


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
