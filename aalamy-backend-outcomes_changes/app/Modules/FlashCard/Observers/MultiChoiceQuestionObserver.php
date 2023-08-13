<?php

namespace Modules\FlashCard\Observers;

use Modules\FlashCard\Models\MultiChoiceQuestion;


class MultiChoiceQuestionObserver
{


    /**
     * Handle the FlashCard "created" event.
     *
     * @param  MultiChoiceQuestion $multiChoiceQuestion
     * @return void
     */
    public function creating(MultiChoiceQuestion $multiChoiceQuestion)
    {

    }

    /**
     * Handle the MultiChoiceQuestion "created" event.
     *
     * @param  MultiChoiceQuestion $multiChoiceQuestion
     * @return void
     * check if the user logged in by outer service or register normal way
     */
    public function created(MultiChoiceQuestion $multiChoiceQuestion)
    {
    }

    /**
     * Handle the MultiChoiceQuestion "updated" event.
     *
     * @param  MultiChoiceQuestion $multiChoiceQuestion
     * @return void
     */
    public function updated(MultiChoiceQuestion $multiChoiceQuestion)
    {

        /*if($multiChoiceQuestion->isDeletedAsSoft()){
            $this->deleted($multiChoiceQuestion);
        }

        if($multiChoiceQuestion->isRestored()){
            $this->restored($multiChoiceQuestion);
        }*/

    }

    /**
     * Handle the MultiChoiceQuestion "deleted" event.
     *
     * @param  MultiChoiceQuestion $multiChoiceQuestion
     * @return void
     */
    public function deleted(MultiChoiceQuestion $multiChoiceQuestion)
    {
        $multiChoiceQuestion->cascadeSoftDelete();
    }

    /**
     * Handle the MultiChoiceQuestion "restored" event.
     *
     * @param  MultiChoiceQuestion $multiChoiceQuestion
     * @return void
     */
    public function restored(MultiChoiceQuestion $multiChoiceQuestion)
    {
        $multiChoiceQuestion->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the MultiChoiceQuestion "force deleted" event.
     *
     * @param  MultiChoiceQuestion $multiChoiceQuestion
     * @return void
     */
    public function forceDeleted(MultiChoiceQuestion $multiChoiceQuestion)
    {
        //
    }
}
