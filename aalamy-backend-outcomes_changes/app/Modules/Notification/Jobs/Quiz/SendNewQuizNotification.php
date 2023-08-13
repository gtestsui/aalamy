<?php

namespace Modules\Notification\Jobs\Quiz;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\ApprovePostRequestNotification;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\NewPostWaitingApproveRequestNotification;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\NewSurveyWaitingApproveRequestNotification;
use Modules\Notification\Http\Controllers\Classes\Quiz\NewQuizRequestNotification;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\ApproveOrRejectSchoolRequestNotification;
use Modules\Quiz\Models\Quiz;
use Modules\User\Models\User;

class SendNewQuizNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $quiz,$requestStatus;
    private $fromUser;

    public function __construct(Quiz $quiz,User $fromUser)
    {
        $this->quiz = $quiz;
        $this->fromUser = $fromUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = new NewQuizRequestNotification($this->quiz,$this->fromUser);
        $notification->notifyFor();
        $notification->notifyToFirebase();
        $notification->notifyToDataBase();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
