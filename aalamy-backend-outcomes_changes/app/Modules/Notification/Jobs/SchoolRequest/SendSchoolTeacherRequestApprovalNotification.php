<?php

namespace Modules\Notification\Jobs\SchoolRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\ApproveOrRejectSchoolRequestNotification;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\ApproveOrRejectSchoolTeacherRequestNotification;

class SendSchoolTeacherRequestApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $schoolRequest,$requestStatus;
    public function __construct($schoolRequest,$requestStatus)
    {
        $this->schoolRequest = $schoolRequest;
        $this->requestStatus = $requestStatus;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $approveOrRejectSchoolRequestNotification = new ApproveOrRejectSchoolTeacherRequestNotification($this->schoolRequest,$this->requestStatus);
        $approveOrRejectSchoolRequestNotification->notifyFor();
        $approveOrRejectSchoolRequestNotification->notifyToFirebase();
        $approveOrRejectSchoolRequestNotification->notifyToDataBase();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
