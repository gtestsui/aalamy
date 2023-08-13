<?php

namespace Modules\Level\Observers;

use Modules\Level\Models\Level;

class LevelObserver
{
    /**
     * Handle the Level "created" event.
     *
     * @param  \Modules\Level\Models\Level  $level
     * @return void
     */
    public function created(Level $level)
    {
        //
    }

    /**
     * Handle the Level "updated" event.
     *
     * @param  \Modules\Level\Models\Level  $level
     * @return void
     */
    public function updated(Level $level)
    {

        /*if($level->isDeletedAsSoft()){
            $this->deleted($level);
        }

        if($level->isRestored()){
            $this->restored($level);
        }*/
    }

    /**
     * Handle the Level "deleted" event.
     *
     * @param  \Modules\Level\Models\Level  $level
     * @return void
     */
    public function deleted(Level $level)
    {
        $level->cascadeSoftDelete();
    }

    /**
     * Handle the Level "restored" event.
     *
     * @param  \Modules\Level\Models\Level  $level
     * @return void
     */
    public function restored(Level $level)
    {
        $level->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the Level "force deleted" event.
     *
     * @param  \Modules\Level\Models\Level  $level
     * @return void
     */
    public function forceDeleted(Level $level)
    {
        //
    }
}
