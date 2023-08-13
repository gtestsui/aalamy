<?php

namespace Modules\Level\Observers;

use Modules\Level\Models\LevelSubject;

class LevelSubjectObserver
{
    /**
     * Handle the LevelSubject "created" event.
     *
     * @param  \Modules\Level\Models\LevelSubject  $levelSubject
     * @return void
     */
    public function created(LevelSubject $levelSubject)
    {
        //
    }

    /**
     * Handle the LevelSubject "updated" event.
     *
     * @param  \Modules\Level\Models\LevelSubject  $levelSubject
     * @return void
     */
    public function updated(LevelSubject $levelSubject)
    {

        /*if($levelSubject->isDeletedAsSoft()){
            $this->deleted($levelSubject);
        }

        if($levelSubject->isRestored()){
            $this->restored($levelSubject);
        }*/
    }

    /**
     * Handle the LevelSubject "deleted" event.
     *
     * @param  \Modules\Level\Models\LevelSubject  $levelSubject
     * @return void
     */
    public function deleted(LevelSubject $levelSubject)
    {
        $levelSubject->cascadeSoftDelete();
    }

    /**
     * Handle the LevelSubject "restored" event.
     *
     * @param  \Modules\Level\Models\LevelSubject  $levelSubject
     * @return void
     */
    public function restored(LevelSubject $levelSubject)
    {
        $levelSubject->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the LevelSubject "force deleted" event.
     *
     * @param  \Modules\Level\Models\LevelSubject  $levelSubject
     * @return void
     */
    public function forceDeleted(LevelSubject $levelSubject)
    {
        //
    }
}
