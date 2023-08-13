<?php

namespace Modules\Level\Observers;

use Modules\Level\Models\Subject;

class SubjectObserver
{
    /**
     * Handle the Subject "created" event.
     *
     * @param  \Modules\Level\Models\Subject  $subject
     * @return void
     */
    public function created(Subject $subject)
    {
        //
    }

    /**
     * Handle the Subject "updated" event.
     *
     * @param  \Modules\Level\Models\Subject  $subject
     * @return void
     */
    public function updated(Subject $subject)
    {

        /*if($subject->isDeletedAsSoft()){
            $this->deleted($subject);
        }

        if($subject->isRestored()){
            $this->restored($subject);
        }*/
    }

    /**
     * Handle the Subject "deleted" event.
     *
     * @param  \Modules\Level\Models\Subject  $subject
     * @return void
     */
    public function deleted(Subject $subject)
    {
        $subject->cascadeSoftDelete();
    }

    /**
     * Handle the Subject "restored" event.
     *
     * @param  \Modules\Level\Models\Subject  $subject
     * @return void
     */
    public function restored(Subject $subject)
    {
        $subject->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the Subject "force deleted" event.
     *
     * @param  \Modules\Level\Models\Subject  $subject
     * @return void
     */
    public function forceDeleted(Subject $subject)
    {
        //
    }
}
