<?php

namespace Modules\ClassModule\Observers;

use Modules\ClassModule\Models\ClassInfo;

class ClassInfoObserver
{
    /**
     * Handle the ClassModel "created" event.
     *
     * @param  \Modules\ClassModule\Models\ClassInfo  $classInfo
     * @return void
     */
    public function created(ClassInfo $classInfo)
    {
        //
    }

    /**
     * Handle the ClassModel "updated" event.
     *
     * @param  \Modules\ClassModule\Models\ClassInfo  $classInfo
     * @return void
     */
    public function updated(ClassInfo $classInfo)
    {
        /*if($classInfo->isDeletedAsSoft()){
            $this->deleted($classInfo);
        }

        if($classInfo->isRestored()){
            $this->restored($classInfo);
        }*/
    }

    /**
     * Handle the ClassModel "deleted" event.
     *
     * @param  \Modules\ClassModule\Models\ClassInfo  $classInfo
     * @return void
     */
    public function deleted(ClassInfo $classInfo)
    {
        $classInfo->cascadeSoftDelete();

    }

    /**
     * Handle the ClassModel "restored" event.
     *
     * @param  \Modules\ClassModule\Models\ClassInfo  $classInfo
     * @return void
     */
    public function restored(ClassInfo $classInfo)
    {
        $classInfo->cascadeRestoreSoftDelete();

    }

    /**
     * Handle the ClassModel "force deleted" event.
     *
     * @param  \Modules\ClassModule\Models\ClassInfo  $classInfo
     * @return void
     */
    public function forceDeleted(ClassInfo $classInfo)
    {
        //
    }
}
