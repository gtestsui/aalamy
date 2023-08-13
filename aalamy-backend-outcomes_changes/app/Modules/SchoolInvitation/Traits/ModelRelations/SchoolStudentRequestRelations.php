<?php

namespace Modules\SchoolInvitation\Traits\ModelRelations;


use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;

trait SchoolStudentRequestRelations
{

    //Relations
    public function School(){
        return $this->belongsTo('Modules\User\Models\School');
    }

    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student');
    }
}
