<?php


namespace Modules\Notification\Http\Controllers\Classes\SchoolRequest;


use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Modules\Notification\Http\Controllers\Classes\NotificationClass;

use Mail;
use Modules\Notification\Models\Notification;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;

class ApproveOrRejectSchoolStudentRequestNotification extends NotificationClass
{
    public $typeNum = 2;
    private $statusesEn =['approved' => 'approved' , 'rejected' => 'rejected'];
    private $statusesAr =['approved' => 'قبول' , 'rejected' => 'رفض'];
    private $toUser,$fromUser,$schoolRequest,$requestStatus;

    /*
     * @param $schoolRequest ether SchoolTeacherRequest or SchoolStudentRequest
     */
    public function __construct($schoolRequest,$requestStatus)
    {


        $this->schoolRequest =$schoolRequest;
        $this->notificationType = parent::getNotificationType(
            configFromModule('panel.notification_types.approve_or_reject_school_student_request',ApplicationModules::NOTIFICATION_MODULE_NAME)
        );

        $this->notificationTitle = 'joining to school request has been '.$this->statusesEn[$requestStatus];
        $this->notificationTitle_ar = 'تم '.
            $this->statusesAr[$requestStatus].
            ' طلب الانضمام للمدرسة';
        $this->notificationBody = 'joining to school request has been '.$this->statusesEn[$requestStatus];
        $this->notificationBody_ar = 'تم '.
            $this->statusesAr[$requestStatus].
            ' طلب الانضمام للمدرسة';

    }

    public function body(){
        $data = [
            'notificationData' =>[
                'schoolRequest' => $this->schoolRequest,
            ],

        ];
        return array_merge($data,$this->getNotficationBody());

    }

    public function notifyFor(){
		$this->schoolRequest->load([
            ucfirst($this->schoolRequest->to).'.User',
            ucfirst($this->schoolRequest->from).'.User',
        ]);
        $this->toUser = $this->schoolRequest->{ucfirst($this->schoolRequest->to)}->User;
        $this->fromUser = $this->schoolRequest->{ucfirst($this->schoolRequest->from)}->User;


        $this->toUserIds = [$this->fromUser->id];
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
