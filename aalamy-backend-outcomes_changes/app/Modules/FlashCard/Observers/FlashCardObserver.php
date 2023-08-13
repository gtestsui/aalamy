<?php

namespace Modules\FlashCard\Observers;

use App\Http\Controllers\Classes\ServicesClass;
use Modules\FlashCard\Models\FlashCard;


class FlashCardObserver
{


    /**
     * Handle the FlashCard "created" event.
     *
     * @param  FlashCard $flashCard
     * @return void
     */
    public function creating(FlashCard $flashCard)
    {

    }

    /**
     * Handle the FlashCard "created" event.
     *
     * @param  FlashCard $flashCard
     * @return void
     * check if the user logged in by outer service or register normal way
     */
    public function created(FlashCard $flashCard)
    {
    }

    /**
     * Handle the FlashCard "updated" event.
     *
     * @param  FlashCard $flashCard
     * @return void
     */
    public function updated(FlashCard $flashCard)
    {

        /*if($flashCard->isDeletedAsSoft()){
            $this->deleted($flashCard);
        }

        if($flashCard->isRestored()){
            $this->restored($flashCard);
        }*/

    }

    /**
     * Handle the FlashCard "deleted" event.
     *
     * @param  FlashCard $flashCard
     * @return void
     */
    public function deleted(FlashCard $flashCard)
    {
        $flashCard->cascadeSoftDelete();
    }

    /**
     * Handle the FlashCard "restored" event.
     *
     * @param  FlashCard $flashCard
     * @return void
     */
    public function restored(FlashCard $flashCard)
    {
        $flashCard->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the FlashCard "force deleted" event.
     *
     * @param  FlashCard $flashCard
     * @return void
     */
    public function forceDeleted(FlashCard $flashCard)
    {
        //
    }
}
