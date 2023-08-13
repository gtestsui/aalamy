<?php

namespace Modules\ClassModule\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;

trait ClassInfoRelations
{

    //Relations
    public function ClassModel(){
        return $this->belongsTo('Modules\ClassModule\Models\ClassModel','class_id');
    }

    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function LevelSubject(){
        return $this->belongsTo('Modules\Level\Models\LevelSubject','level_subject_id');
    }

    public function Teacher(){
        return $this->belongsTo('Modules\User\Models\Teacher','teacher_id');
    }

    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function Rosters(){
        return $this->hasMany(Roster::class,'class_info_id');
    }


}
