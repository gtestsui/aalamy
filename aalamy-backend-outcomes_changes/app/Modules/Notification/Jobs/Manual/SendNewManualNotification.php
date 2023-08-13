<?php

namespace Modules\Notification\Jobs\Manual;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest\EducatorRosterStudentRequestNotification;
use Modules\Notification\Http\Controllers\Classes\Event\NewEventNotification;
use Modules\Notification\Http\Controllers\Classes\Manual\NewManualNotification;
use Modules\Notification\Models\ManualNotification;
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class SendNewManualNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var User $fromUser
     * @var int[] $parentUserIds
     * @var int[] $studentUserIds
     * @var int[] $teacherUserIdsWithTeacherIdAsKeys
     * @var ManualNotification $manualNotification
     * @return void
     */
    private $toUserIds,$fromUser,$manualNotification;
    public function __construct(array $parentUserIds,array $studentUserIds,array $teacherUserIdsWithTeacherIdAsKeys,User $fromUser,ManualNotification $manualNotification)
    {
        $this->parentUserIds   = $parentUserIds;
        $this->studentUserIds   = $studentUserIds;
        $this->teacherUserIdsWithTeacherIdAsKeys   = $teacherUserIdsWithTeacherIdAsKeys;
        $this->manualNotification     = $manualNotification;
        $this->fromUser    = $fromUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $arrayOfArraysOfUserIds = [
            $this->parentUserIds ,
            $this->studentUserIds
        ];
        $newManualNotification = new NewManualNotification(
            $arrayOfArraysOfUserIds,
            $this->teacherUserIdsWithTeacherIdAsKeys,
            $this->manualNotification,
            $this->fromUser
        );
        $newManualNotification->notifyFor();
        /**
         * @var string[] $types
         * this will contain array of string to send notification by it (email,push,..)
         */
        $types = $this->manualNotification->send_by_types;

        if(in_array(config('Notification.panel.send_by_types.push'),$types)){

            $newManualNotification->notifyToFirebase();
            $newManualNotification->notifyToDataBase();
        }
        if(in_array(config('Notification.panel.send_by_types.email'),$types)) {
            $newManualNotification->notifyToMail();
        }
    }
}
