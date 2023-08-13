<?php

namespace Modules\Roster\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;

trait RosterStudentRelations
{

    //Relations
    public function Roster(){
        return $this->belongsTo('Modules\Roster\Models\Roster','roster_id');
    }

    public function ClassStudent(){
        return $this->belongsTo('Modules\ClassModule\Models\ClassStudent','class_student_id');
    }


}
