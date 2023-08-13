<?php

namespace Modules\Feedback\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait FeedbackAboutStudentFileRelations
{

    //Relations
    public function Feedback(){
        return $this->belongsTo('Modules\Feedback\Models\FeedbackAboutStudent','feedback_id');
    }


}
