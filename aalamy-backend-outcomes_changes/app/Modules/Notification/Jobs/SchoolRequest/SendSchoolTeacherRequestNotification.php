<?php

namespace Modules\Notification\Jobs\SchoolRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\SchoolTeacherRequestNotification;

class SendSchoolTeacherRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $teacherRequest;
    public function __construct($teacherRequest)
    {
        $this->teacherRequest = $teacherRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schoolTeacherRequestNotification = new SchoolTeacherRequestNotification($this->teacherRequest);
        $schoolTeacherRequestNotification->notifyFor();
        $schoolTeacherRequestNotification->notifyToFirebase();
        $schoolTeacherRequestNotification->notifyToDataBase();
        $schoolTeacherRequestNotification->notifyToMail();
    }
}
