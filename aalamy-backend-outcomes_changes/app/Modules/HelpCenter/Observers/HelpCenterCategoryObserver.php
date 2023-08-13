<?php

namespace Modules\HelpCenter\Observers;

use Modules\HelpCenter\Models\HelpCenterCategory;

class HelpCenterCategoryObserver
{
    /**
     * Handle the HelpCenterCategory "created" event.
     *
     * @param  \Modules\HelpCenter\Models\HelpCenterCategory  $helpCenterCategory
     * @return void
     */
    public function created(HelpCenterCategory $helpCenterCategory)
    {
        //
    }

    /**
     * Handle the HelpCenterCategory "updated" event.
     *
     * @param  \Modules\HelpCenter\Models\HelpCenterCategory  $helpCenterCategory
     * @return void
     */
    public function updated(HelpCenterCategory $helpCenterCategory)
    {

        /*if($helpCenterCategory->isDeletedAsSoft()){
            $this->deleted($helpCenterCategory);
        }

        if($helpCenterCategory->isRestored()){
            $this->restored($helpCenterCategory);
        }*/
    }

    /**
     * Handle the HelpCenterCategory "deleted" event.
     *
     * @param  \Modules\HelpCenter\Models\HelpCenterCategory  $helpCenterCategory
     * @return void
     */
    public function deleted(HelpCenterCategory $helpCenterCategory)
    {
        $helpCenterCategory->cascadeSoftDelete();
    }

    /**
     * Handle the HelpCenterCategory "restored" event.
     *
     * @param  \Modules\HelpCenter\Models\HelpCenterCategory  $helpCenterCategory
     * @return void
     */
    public function restored(HelpCenterCategory $helpCenterCategory)
    {
        $helpCenterCategory->cascadeRestoreSoftDelete();
    }

    /**
     * Handle the HelpCenterCategory "force deleted" event.
     *
     * @param  \Modules\HelpCenter\Models\HelpCenterCategory  $helpCenterCategory
     * @return void
     */
    public function forceDeleted(HelpCenterCategory $helpCenterCategory)
    {
        //
    }
}
