<?php

namespace Modules\Event\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\Event;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait EventTargetRelations
{

    //Relations
    public function Event(){
        return $this->belongsTo(Event::class,'event_id');
    }

    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student','student_id');
    }

    public function Parent(){
        return $this->belongsTo('Modules\User\Models\ParentModel','parent_id');
    }

    public function Teacher(){
        return $this->belongsTo('Modules\User\Models\Teacher','teacher_id');
    }



}
