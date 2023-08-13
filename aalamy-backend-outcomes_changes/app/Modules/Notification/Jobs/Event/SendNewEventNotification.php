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
use Modules\Roster\Models\Roster;
use Modules\User\Models\User;

class SendNewEventNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @var User $fromUser
     * @var array $targetUserArray
     * @var Event $event
     * @return void
     */
    private $targetUserArray,$fromUser,$event;
    public function __construct(array $targetUserArray,User $fromUser,Event $event)
    {
        $this->targetUserArray   = $targetUserArray;
        $this->fromUser     = $fromUser;
        $this->event       = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newEventNotification = new NewEventNotification($this->targetUserArray,$this->event,$this->fromUser );
        $newEventNotification->notifyFor();
        $newEventNotification->notifyToFirebase();
        $newEventNotification->notifyToDataBase();
//        $newEventNotification->notifyToMail();
    }
}
