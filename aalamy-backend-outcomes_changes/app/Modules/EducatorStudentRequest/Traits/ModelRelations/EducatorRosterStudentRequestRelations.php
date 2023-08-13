<?php

namespace Modules\EducatorStudentRequest\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;

trait EducatorRosterStudentRequestRelations
{

    //Relations
    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student','student_id');
    }

    public function Roster(){
        return $this->belongsTo('Modules\Roster\Models\Roster','roster_id');
    }


}
