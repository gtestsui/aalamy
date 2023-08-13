<?php

namespace Modules\ClassModule\Observers;

use Modules\ClassModule\Models\ClassStudent;

class ClassStudentObserver
{
    /**
     * Handle the ClassModel "created" event.
     *
     * @param  \Modules\ClassModule\Models\ClassStudent  $classStudent
     * @return void
     */
    public function created(ClassStudent $classStudent)
    {
        //
    }

    /**
     * Handle the ClassModel "updated" event.
     *
     * @param  \Modules\ClassModule\Models\ClassStudent  $classStudent
     * @return void
     */
    public function updated(ClassStudent $classStudent)
    {
        /*if($classStudent->isDeletedAsSoft()){
            $this->deleted($classStudent);
        }

        if($classStudent->isRestored()){
            $this->restored($classStudent);
        }*/
    }

    /**
     * Handle the ClassModel "deleted" event.
     *
     * @param  \Modules\ClassModule\Models\ClassStudent  $classStudent
     * @return void
     */
    public function deleted(ClassStudent $classStudent)
    {
        $classStudent->cascadeSoftDelete();

    }

    /**
     * Handle the ClassModel "restored" event.
     *
     * @param  \Modules\ClassModule\Models\ClassStudent  $classStudent
     * @return void
     */
    public function restored(ClassStudent $classStudent)
    {
        $classStudent->cascadeRestoreSoftDelete();

    }

    /**
     * Handle the ClassModel "force deleted" event.
     *
     * @param  \Modules\ClassModule\Models\ClassStudent  $classStudent
     * @return void
     */
    public function forceDeleted(ClassStudent $classStudent)
    {
        //
    }
}
