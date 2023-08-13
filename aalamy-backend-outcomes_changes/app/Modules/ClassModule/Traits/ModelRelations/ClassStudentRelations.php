<?php

namespace Modules\ClassModule\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\RosterStudent;

trait ClassStudentRelations
{

    //Relations
    public function ClassModel(){
        return $this->belongsTo('Modules\ClassModule\Models\ClassModel','class_id');
    }

    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student','student_id');
    }


    public function Teacher(){
        return $this->belongsTo('Modules\User\Models\Teacher','teacher_id');
    }

    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function RosterStudents(){
        return $this->hasMany(RosterStudent::class,'class_student_id');
    }

}
