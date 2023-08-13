<?php

namespace Modules\Notification\Jobs\Meeting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Meeting\Models\Meeting;
use Modules\Notification\Http\Controllers\Classes\Meeting\NewMeetingNotification;

class SendNewMeetingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
//     * @var User $fromUser
     * @var array $targetUserArray
     * @var Meeting $meeting
     * @return void
     */
    private $targetUserArray,$meeting;
    public function __construct(array $targetUserArray,Meeting $meeting)
    {
        $this->targetUserArray   = $targetUserArray;
//        $this->fromUser     = $fromUser;
        $this->meeting      = $meeting;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newMeetingNotification = new NewMeetingNotification($this->targetUserArray,$this->meeting );
        $newMeetingNotification->notifyFor();
        $newMeetingNotification->notifyToFirebase();
        $newMeetingNotification->notifyToDataBase();
//        $newEventNotification->notifyToMail();
    }
}
