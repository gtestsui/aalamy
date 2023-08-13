<?php

namespace Modules\Roster\Observers;

use Modules\Roster\Models\Roster;

class RosterObserver
{
    /**
     * Handle the Roster "created" event.
     *
     * @param  \Modules\Roster\Models\Roster  $roster
     * @return void
     */
    public function created(Roster $roster)
    {
        //
    }

    /**
     * Handle the Roster "updated" event.
     *
     * @param  \Modules\Roster\Models\Roster  $roster
     * @return void
     */
    public function updated(Roster $roster)
    {

        /*if($roster->isDeletedAsSoft()){
            $this->deleted($roster);
        }

        if($roster->isRestored()){
            $this->restored($roster);
        }*/
    }

    /**
     * Handle the Roster "deleted" event.
     *
     * @param  \Modules\Roster\Models\Roster  $roster
     * @return void
     */
    public function deleted(Roster $roster)
    {
        $roster->cascadeSoftDelete();
    }

    /**
     * Handle the Roster "restored" event.
     *
     * @param  \Modules\Roster\Models\Roster  $roster
     * @return void
     */
    public function restored(Roster $roster)
    {
        $roster->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the Roster "force deleted" event.
     *
     * @param  \Modules\Roster\Models\Roster  $roster
     * @return void
     */
    public function forceDeleted(Roster $roster)
    {
        //
    }
}
