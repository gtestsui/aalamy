<?php

namespace Modules\Notification\Jobs\SchoolRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\SchoolStudentRequestNotification;

class SendSchoolStudentRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $studentRequest;
    public function __construct($studentRequest)
    {
        $this->studentRequest = $studentRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $schoolStudentRequestNotification = new SchoolStudentRequestNotification($this->studentRequest);
        $schoolStudentRequestNotification->notifyFor();
        $schoolStudentRequestNotification->notifyToFirebase();
        $schoolStudentRequestNotification->notifyToDataBase();
        $schoolStudentRequestNotification->notifyToMail();
    }
}
