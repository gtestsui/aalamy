<?php

namespace Modules\Feedback\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait FeedbackAboutStudentAttendanceRelations
{

    //Relations
    public function Feedback(){
        return $this->belongsTo('Modules\Feedback\Models\FeedbackAboutStudentAttendance','feedback_id');
    }

    public function Assignment(){
        return $this->belongsTo('Modules\Assignment\Models\Assignment','assignment_id');
    }


}
