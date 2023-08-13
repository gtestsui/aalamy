<?php

namespace Modules\Level\Observers;

use Modules\Level\Models\Lesson;

class LessonObserver
{
    /**
     * Handle the Lesson "created" event.
     *
     * @param  \Modules\Level\Models\Lesson  $leeson
     * @return void
     */
    public function created(Lesson $leeson)
    {
        //
    }

    /**
     * Handle the Lesson "updated" event.
     *
     * @param  \Modules\Level\Models\Lesson  $leeson
     * @return void
     */
    public function updated(Lesson $leeson)
    {

        /*if($leeson->isDeletedAsSoft()){
            $this->deleted($leeson);
        }

        if($leeson->isRestored()){
            $this->restored($leeson);
        }*/
    }

    /**
     * Handle the Lesson "deleted" event.
     *
     * @param  \Modules\Level\Models\Lesson  $leeson
     * @return void
     */
    public function deleted(Lesson $leeson)
    {
        $leeson->cascadeSoftDelete();
    }

    /**
     * Handle the Lesson "restored" event.
     *
     * @param  \Modules\Level\Models\Lesson  $leeson
     * @return void
     */
    public function restored(Lesson $leeson)
    {
        $leeson->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the Lesson "force deleted" event.
     *
     * @param  \Modules\Level\Models\Lesson  $leeson
     * @return void
     */
    public function forceDeleted(Lesson $leeson)
    {
        //
    }
}
