<?php

namespace Modules\Event\Observers;

use Modules\Event\Models\Event;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \Modules\Event\Models\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        //
    }

    /**
     * Handle the Event "updated" event.
     *
     * @param  \Modules\Event\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        /*if($event->isDeletedAsSoft()){
            $this->deleted($event);
        }

        if($event->isRestored()){
            $this->restored($event);
        }*/
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \Modules\Event\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
//        $event->cascadeSoftDelete();

    }

    /**
     * Handle the Event "restored" event.
     *
     * @param  \Modules\Event\Models\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
//        $event->cascadeRestoreSoftDelete();

    }

    /**
     * Handle the Event "force deleted" event.
     *
     * @param  \Modules\Event\Models\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}
