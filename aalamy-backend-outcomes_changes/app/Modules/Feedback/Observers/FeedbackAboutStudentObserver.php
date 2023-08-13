<?php

namespace Modules\Feedback\Observers;

use App\Http\Controllers\Classes\ServicesClass;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Notification\Jobs\Feedback\SendFeedbackAboutStudentNotification;


class FeedbackAboutStudentObserver
{


    /**
     * Handle the FeedbackAboutStudent "created" event.
     *
     * @param  FeedbackAboutStudent $feedbackAboutStudent
     * @return void
     */
    public function creating(FeedbackAboutStudent $feedbackAboutStudent)
    {

    }

    /**
     * Handle the FeedbackAboutStudent "created" event.
     *
     * @param  FeedbackAboutStudent $feedbackAboutStudent
     * @return void
     * check if the user logged in by outer service or register normal way
     */
    public function created(FeedbackAboutStudent $feedbackAboutStudent)
    {
        //the feedback saved and published at the same time
        if ($feedbackAboutStudent->share_with_parent)
            ServicesClass::dispatchJob(new SendFeedbackAboutStudentNotification($feedbackAboutStudent));

    }

    /**
     * Handle the FeedbackAboutStudent "updated" event.
     *
     * @param  FeedbackAboutStudent $feedbackAboutStudent
     * @return void
     */
    public function updated(FeedbackAboutStudent $feedbackAboutStudent)
    {
        //the feedback has been published

        if($feedbackAboutStudent->wasChanged('share_with_parent')){
            // share_with_parent has changed
            $new_value = $feedbackAboutStudent->share_with_parent;
            $old_value = $feedbackAboutStudent->getOriginal('share_with_parent');
            if($old_value != $new_value && $new_value)
                ServicesClass::dispatchJob(new SendFeedbackAboutStudentNotification($feedbackAboutStudent));
        }

        /*if($feedbackAboutStudent->isDeletedAsSoft()){
            $this->deleted($feedbackAboutStudent);
        }

        if($feedbackAboutStudent->isRestored()){
            $this->restored($feedbackAboutStudent);
        }*/

    }

    /**
     * Handle the FeedbackAboutStudent "deleted" event.
     *
     * @param  FeedbackAboutStudent $feedbackAboutStudent
     * @return void
     */
    public function deleted(FeedbackAboutStudent $feedbackAboutStudent)
    {
        $feedbackAboutStudent->cascadeSoftDelete();
    }

    /**
     * Handle the FeedbackAboutStudent "restored" event.
     *
     * @param  FeedbackAboutStudent $feedbackAboutStudent
     * @return void
     */
    public function restored(FeedbackAboutStudent $feedbackAboutStudent)
    {
        $feedbackAboutStudent->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the FeedbackAboutStudent "force deleted" event.
     *
     * @param  FeedbackAboutStudent $feedbackAboutStudent
     * @return void
     */
    public function forceDeleted(FeedbackAboutStudent $feedbackAboutStudent)
    {
        //
    }
}
