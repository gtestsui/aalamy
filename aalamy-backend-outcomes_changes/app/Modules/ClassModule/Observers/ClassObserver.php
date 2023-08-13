<?php

namespace Modules\ClassModule\Observers;

use Modules\ClassModule\Models\ClassModel;

class ClassObserver
{
    /**
     * Handle the ClassModel "created" event.
     *
     * @param  \Modules\ClassModule\Models\ClassModel  $classModel
     * @return void
     */
    public function created(ClassModel $classModel)
    {
        //
    }

    /**
     * Handle the ClassModel "updated" event.
     *
     * @param  \Modules\ClassModule\Models\ClassModel  $classModel
     * @return void
     */
    public function updated(ClassModel $classModel)
    {
        /*if($classModel->isDeletedAsSoft()){
            $this->deleted($classModel);
        }

        if($classModel->isRestored()){
            $this->restored($classModel);
        }*/
    }

    /**
     * Handle the ClassModel "deleted" event.
     *
     * @param  \Modules\ClassModule\Models\ClassModel  $classModel
     * @return void
     */
    public function deleted(ClassModel $classModel)
    {
        $classModel->cascadeSoftDelete();

    }

    /**
     * Handle the ClassModel "restored" event.
     *
     * @param  \Modules\ClassModule\Models\ClassModel  $classModel
     * @return void
     */
    public function restored(ClassModel $classModel)
    {
        $classModel->cascadeRestoreSoftDelete();

    }

    /**
     * Handle the ClassModel "force deleted" event.
     *
     * @param  \Modules\ClassModule\Models\ClassModel  $classModel
     * @return void
     */
    public function forceDeleted(ClassModel $classModel)
    {
        //
    }
}
