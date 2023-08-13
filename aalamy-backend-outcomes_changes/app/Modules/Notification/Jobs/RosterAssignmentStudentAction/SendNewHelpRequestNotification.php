<?php

namespace Modules\Notification\Jobs\RosterAssignmentStudentAction;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\ApprovePostRequestNotification;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\NewPostWaitingApproveRequestNotification;
use Modules\Notification\Http\Controllers\Classes\RosterAssignmentStudentAction\SendHelpRequestNotification;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\ApproveOrRejectSchoolRequestNotification;
use Modules\RosterAssignment\Models\RosterAssignmentStudentAction;
use Modules\User\Models\User;

class SendNewHelpRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $rosterAssignmentStudentAction;

    public function __construct(RosterAssignmentStudentAction $rosterAssignmentStudentAction)
    {
        $this->rosterAssignmentStudentAction = $rosterAssignmentStudentAction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = new SendHelpRequestNotification($this->rosterAssignmentStudentAction);
        $notification->notifyFor();
        $notification->notifyToFirebase();
        $notification->notifyToDataBase();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
