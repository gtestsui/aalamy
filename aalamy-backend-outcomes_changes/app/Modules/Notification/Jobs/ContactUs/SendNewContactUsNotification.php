<?php

namespace Modules\Notification\Jobs\ContactUs;

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
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class SendNewContactUsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var User $fromUser
     * @var array $targetUserArray
     * @var Event $event
     * @return void
     */
    private $fromUser,$contactUs;
    public function __construct(ContactUs $contactUs,User $fromUser)
    {
        $this->fromUser     = $fromUser;
        $this->contactUs    = $contactUs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newContactUsNotification = new NewContactUsNotification($this->contactUs,$this->fromUser);
        $newContactUsNotification->notifyFor();
        $newContactUsNotification->notifyToFirebase();
        $newContactUsNotification->notifyToDataBase();
//        $newEventNotification->notifyToMail();
    }
}
