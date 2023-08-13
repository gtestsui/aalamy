<?php

namespace Modules\Notification\Jobs\RosterAssignment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Notification\Http\Controllers\Classes\RosterAssignment\NewRosterAssignmentRequestNotification;
use Modules\User\Models\User;

class SendNewRosterAssignmentNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $assignmentIds,$rosterIds;
    private $fromUser;

    public function __construct($assignmentIds,$rosterIds,User $fromUser)
    {
        $this->assignmentIds = $assignmentIds;
        $this->rosterIds = $rosterIds;
        $this->fromUser = $fromUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = new NewRosterAssignmentRequestNotification($this->assignmentIds,$this->rosterIds,$this->fromUser);
        $notification->notify();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
