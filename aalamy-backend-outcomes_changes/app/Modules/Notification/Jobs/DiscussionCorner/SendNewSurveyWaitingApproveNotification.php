<?php

namespace Modules\Notification\Jobs\DiscussionCorner;

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
use Modules\Notification\Http\Controllers\Classes\SchoolRequest\ApproveOrRejectSchoolRequestNotification;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class SendNewSurveyWaitingApproveNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $discussionCornerSurvey,$requestStatus;
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

        if(!is_null($this->discussionCornerSurvey->educator_id)){
            if(UserServices::isEducator($this->fromUser)){
                return;
            }
        }else{
            if(UserServices::isSchool($this->fromUser)){
                return;
            }
        }
        $notification = new NewSurveyWaitingApproveRequestNotification($this->discussionCornerSurvey,$this->fromUser);
        $notification->notifyFor();
        $notification->notifyToFirebase();
        $notification->notifyToDataBase();
//        $approveOrRejectSchoolRequestNotification->notifyToMail();
    }
}
