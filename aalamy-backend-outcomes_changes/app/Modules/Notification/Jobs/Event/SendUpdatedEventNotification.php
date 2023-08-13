<?php

namespace Modules\Notification\Jobs\Event;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Event\Models\Event;
use Modules\Notification\Http\Controllers\Classes\EducatorRosterStudentRequest\EducatorRosterStudentRequestNotification;
use Modules\Notification\Http\Controllers\Classes\Event\NewEventNotification;
use Modules\Notification\Http\Controllers\Classes\Event\UpdatedEventNotification;
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class SendUpdatedEventNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var Event $event
     * @return void
     */
    private $event;
    public function __construct(Event $event)
    {
        $this->event       = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updatedEventNotification = new UpdatedEventNotification($this->event);
        $updatedEventNotification->notifyFor();
        $updatedEventNotification->notifyToFirebase();
        $updatedEventNotification->notifyToDataBase();
//        $newEventNotification->notifyToMail();
    }
}
