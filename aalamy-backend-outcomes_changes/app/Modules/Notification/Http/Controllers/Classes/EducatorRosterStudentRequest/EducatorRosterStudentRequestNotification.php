<?php


namespace Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class EducatorRosterStudentRequestNotification extends NotificationClass
{
    private $fromUser;
    private $studentIds;
    private $roster;
    private $introductoryMessage;


    public function __construct(array $studentIds,User $fromUser,Roster $roster,$introductoryMessage=null)
    {
        $this->studentIds =$studentIds;
        $this->fromUser =$fromUser;
        $this->notificationImage = $this->fromUser->image;

        $this->roster =$roster;
        $this->introductoryMessage =$introductoryMessage;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.educator_roster_student_request',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'Joining to roster request';
        $this->notificationTitle_ar = 'طلب انضمام الى الحصة';
        $this->notificationBody = 'Joining to roster request';
        $this->notificationBody_ar = 'طلب انضمام الى الحصة';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'roster' => $this->roster,
            ],
        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){

        $userIds = Student::whereIn('id',$this->studentIds)
            ->pluck('user_id')->toArray();
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
