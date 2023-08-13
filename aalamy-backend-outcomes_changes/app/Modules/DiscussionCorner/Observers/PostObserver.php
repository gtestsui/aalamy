<?php

namespace App\Modules\DiscussionCorner\Observers;

use App\Http\Controllers\Classes\ServicesClass;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\Notification\Jobs\DiscussionCorner\SendPostApprovalNotification;

class PostObserver
{
    /**
     * Handle the DiscussionCornerPost "created" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerPost  $discussionCornerPost
     * @return void
     */
    public function created(DiscussionCornerPost $discussionCornerPost)
    {
        //
    }

    /**
     * Handle the DiscussionCornerPost "updated" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerPost  $discussionCornerPost
     * @return void
     */
    public function updated(DiscussionCornerPost $discussionCornerPost)
    {
        //the post ether approved or its updated so then need new approval
        if($discussionCornerPost->wasChanged('is_approved') && $discussionCornerPost->is_approved == 1){
            //here send post approved notification to $post->user_id

//            ServicesClass::dispatchJob(new SendPostApprovalNotification($discussionCornerPost));
        }

        /*if($discussionCornerPost->isDeletedAsSoft()){
            $this->deleted($discussionCornerPost);
        }

        if($discussionCornerPost->isRestored()){
            $this->restored($discussionCornerPost);
        }*/

    }

    /**
     * Handle the DiscussionCornerPost "deleted" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerPost  $discussionCornerPost
     * @return void
     */
    public function deleted(DiscussionCornerPost $discussionCornerPost)
    {
    }

    /**
     * Handle the DiscussionCornerPost "restored" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerPost  $discussionCornerPost
     * @return void
     */
    public function restored(DiscussionCornerPost $discussionCornerPost)
    {
    }

    /**
     * Handle the DiscussionCornerPost "force deleted" event.
     *
     * @param  \Modules\DiscussionCorner\Models\DiscussionCornerPost  $discussionCornerPost
     * @return void
     */
    public function forceDeleted(DiscussionCornerPost $discussionCornerPost)
    {
        //
    }
}
