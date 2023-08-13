<?php

namespace Modules\SchoolInvitation\Traits\ModelRelations;


use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;

trait SchoolTeacherRequestRelations
{

    // Relations
    public function School(){
        return $this->belongsTo('Modules\User\Models\School');
    }

    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator');
    }
}
