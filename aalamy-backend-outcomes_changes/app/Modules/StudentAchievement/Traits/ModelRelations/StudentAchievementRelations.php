<?php

namespace Modules\StudentAchievement\Traits\ModelRelations;


use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\Roster\Models\RosterStudent;

trait StudentAchievementRelations
{

    //Relations
    public function Student(){
        return $this->belongsTo('Modules\User\Models\Student','student_id');
    }

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

}
