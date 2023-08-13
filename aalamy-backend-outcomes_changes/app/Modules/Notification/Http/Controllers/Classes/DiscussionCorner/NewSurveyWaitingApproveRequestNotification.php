<?php


namespace Modules\Notification\Http\Controllers\Classes\DiscussionCorner;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\User;

class NewSurveyWaitingApproveRequestNotification extends NotificationClass
{
    public  $typeNum = 3;
    private $discussionCornerSurvey;
    private $fromUser;

    public function __construct(DiscussionCornerSurvey $discussionCornerSurvey,User $fromUser)
    {
        $this->discussionCornerSurvey = $discussionCornerSurvey;
        $this->fromUser = $fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.new_survey_waiting_approve',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = "there is a new survey waiting your approve from ".$fromUser->getFullName();
        $this->notificationTitle_ar = 'تمت اضافة استبيان جديد ينتظر موافقتك '.
            $fromUser->getFullName();
        $this->notificationBody = 'there is a new survey waiting your approve from '.$fromUser->getFullName();
        $this->notificationBody_ar = 'تمت اضافة استبيان جديد ينتظر موافقتك '.
            $fromUser->getFullName();

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

        if(!is_null($this->discussionCornerSurvey->educator_id)){
            $toUserId = Educator::findOrFail($this->discussionCornerSurvey->educator_id)->user_id;
        }else{
            $toUserId = School::findOrFail($this->discussionCornerSurvey->school_id)->user_id;

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
