<?php

namespace Modules\Notification\Jobs\DiscussionCorner;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\ApproveSurveyRequestNotification;
use Modules\User\Models\User;

class SendSurveyApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $discussionCornerSurvey;
    private $requestStatus;
    private $fromUser;
    public function __construct(DiscussionCornerSurvey $discussionCornerSurvey,User $fromUser)
    {
        $this->discussionCornerSurvey = $discussionCornerSurvey;
        $this->fromUser = $fromUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $approveSurveyRequestNotification = new ApproveSurveyRequestNotification($this->discussionCornerSurvey,$this->fromUser);
        $approveSurveyRequestNotification->notifyFor();
        $approveSurveyRequestNotification->notifyToFirebase();
        $approveSurveyRequestNotification->notifyToDataBase();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
