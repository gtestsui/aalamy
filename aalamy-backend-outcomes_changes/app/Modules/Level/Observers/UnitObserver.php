<?php

namespace Modules\Level\Observers;

use Modules\Level\Models\Unit;

class UnitObserver
{
    /**
     * Handle the Unit "created" event.
     *
     * @param  \Modules\Level\Models\Unit  $unit
     * @return void
     */
    public function created(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "updated" event.
     *
     * @param  \Modules\Level\Models\Unit  $unit
     * @return void
     */
    public function updated(Unit $unit)
    {

        /*if($unit->isDeletedAsSoft()){
            $this->deleted($unit);
        }

        if($unit->isRestored()){
            $this->restored($unit);
        }*/
    }

    /**
     * Handle the Unit "deleted" event.
     *
     * @param  \Modules\Level\Models\Unit  $unit
     * @return void
     */
    public function deleted(Unit $unit)
    {
        $unit->cascadeSoftDelete();
    }

    /**
     * Handle the Unit "restored" event.
     *
     * @param  \Modules\Level\Models\Unit  $unit
     * @return void
     */
    public function restored(Unit $unit)
    {
        $unit->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the Unit "force deleted" event.
     *
     * @param  \Modules\Level\Models\Unit  $unit
     * @return void
     */
    public function forceDeleted(Unit $unit)
    {
        //
    }
}
