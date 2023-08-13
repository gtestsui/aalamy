<?php

namespace Modules\SchoolInvitation\Traits\ModelRelations;


use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;

trait SchoolTeacherInvitationRelations
{

    // Relations
    public function School(){
        return $this->belongsTo('Modules\User\Models\School');
    }
}
