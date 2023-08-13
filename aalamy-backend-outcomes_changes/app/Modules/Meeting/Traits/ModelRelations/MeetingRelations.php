<?php

namespace Modules\Meeting\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\Roster\Models\Roster;

trait MeetingRelations
{

    //Relations
    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function Teacher(){
        return $this->belongsTo('Modules\User\Models\Teacher','teacher_id');
    }

    public function TargetUsers(){
        return $this->hasMany(MeetingTargetUser::class,'meeting_id');
    }



}
