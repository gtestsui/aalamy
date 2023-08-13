<?php

namespace App\Modules\DiscussionCorner\Observers;

use App\Http\Controllers\Classes\ServicesClass;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\Notification\Jobs\DiscussionCorner\SendSurveyApprovalNotification;

class SurveyObserver
{
    /**
     * Handle the DiscussionCornerPost "created" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerSurvey  $discussionCornerSurvey
     * @return void
     */
    public function created(DiscussionCornerSurvey $discussionCornerSurvey)
    {
        //
    }

    /**
     * Handle the DiscussionCornerPost "updated" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerSurvey  $discussionCornerSurvey
     * @return void
     */
    public function updated(DiscussionCornerSurvey $discussionCornerSurvey)
    {
        if($discussionCornerSurvey->wasChanged('is_approved') && $discussionCornerSurvey->is_approved == 1){
            //here send post approved notification to $post->user_id
//            ServicesClass::dispatchJob(new SendSurveyApprovalNotification($discussionCornerSurvey));

        }

        /*if($discussionCornerSurvey->isDeletedAsSoft()){
            $this->deleted($discussionCornerSurvey);
        }

        if($discussionCornerSurvey->isRestored()){
            $this->restored($discussionCornerSurvey);
        }*/

    }

    /**
     * Handle the DiscussionCornerPost "deleted" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerSurvey  $discussionCornerSurvey
     * @return void
     */
    public function deleted(DiscussionCornerSurvey $discussionCornerSurvey)
    {
        $discussionCornerSurvey->cascadeSoftDelete();
    }

    /**
     * Handle the DiscussionCornerPost "restored" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerSurvey  $discussionCornerSurvey
     * @return void
     */
    public function restored(DiscussionCornerSurvey $discussionCornerSurvey)
    {
        $discussionCornerSurvey->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the DiscussionCornerPost "force deleted" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerSurvey  $discussionCornerSurvey
     * @return void
     */
    public function forceDeleted(DiscussionCornerSurvey $discussionCornerSurvey)
    {
        //
    }
}
