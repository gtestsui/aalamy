<?php

namespace Modules\Meeting\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Meeting\Models\Meeting;
use Modules\Meeting\Models\MeetingTargetUser;
use Modules\Roster\Models\Roster;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

trait MeetingTargetUserRelations
{

    //Relations
    public function Meeting(){
        return $this->belongsTo(Meeting::class,'meeting_id');
    }

    public function Parent(){
        return $this->belongsTo(ParentModel::class,'parent_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class,'student_id');
    }



}
