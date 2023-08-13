<?php

namespace Modules\Feedback\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait FeedbackAboutStudentMarkRelations
{

    //Relations
    public function Feedback(){
        return $this->belongsTo('Modules\Feedback\Models\FeedbackAboutStudentAttendance','feedback_id');
    }



}
