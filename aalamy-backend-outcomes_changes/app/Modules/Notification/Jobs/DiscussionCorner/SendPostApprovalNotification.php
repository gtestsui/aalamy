<?php

namespace Modules\Notification\Jobs\DiscussionCorner;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Notification\Http\Controllers\Classes\DiscussionCorner\ApprovePostRequestNotification;
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\ApproveOrRejectSchoolRequestNotification;
use Modules\User\Models\User;

class SendPostApprovalNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $discussionCornerPost,$requestStatus;
    private $fromUser;

    public function __construct(DiscussionCornerPost $discussionCornerPost,User $fromUser)
    {
        $this->discussionCornerPost = $discussionCornerPost;
        $this->fromUser = $fromUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $approvePostRequestNotification = new ApprovePostRequestNotification($this->discussionCornerPost,$this->fromUser);
        $approvePostRequestNotification->notifyFor();
        $approvePostRequestNotification->notifyToFirebase();
        $approvePostRequestNotification->notifyToDataBase();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
