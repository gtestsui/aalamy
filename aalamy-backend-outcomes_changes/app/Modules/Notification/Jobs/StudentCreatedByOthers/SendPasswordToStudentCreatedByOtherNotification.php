<?php

namespace Modules\Notification\Jobs\StudentCreatedByOthers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\ContactUs\Models\ContactUs;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\ContactUs\NewContactUsNotification;
use Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest\EducatorRosterStudentRequestNotification;
use Modules\Notification\Http\Controllers\Classes\Event\NewEventNotification;
use Modules\Notification\Http\Controllers\Classes\StudentCreatedByOthers\PasswordToStudentCreatedByOtherNotification;
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class SendPasswordToStudentCreatedByOtherNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var User $user the user of student (we will send the information for him)
     * @var string $password
     * @return void
     */
    private $arrayOfEmailsAndPasswords;
    public function __construct($arrayOfEmailsAndPasswords)
    {
        $this->arrayOfEmailsAndPasswords     = $arrayOfEmailsAndPasswords;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = new PasswordToStudentCreatedByOtherNotification($this->arrayOfEmailsAndPasswords);
        $notification->notifyToMail();
    }
}
