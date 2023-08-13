<?php

namespace Modules\Outcomes\Traits\ModelRelations;


use Modules\Level\Models\Level;
use Modules\Outcomes\Models\Mark;
use Modules\User\Models\School;
use Modules\User\Models\Student;

trait StudentStudyingInformationRelations
{

    //Relations
    public function Student(){
        return $this->belongsTo(Student::class,'student_id');
    }

    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Level(){
        return $this->belongsTo(Level::class,'level_id');
    }

    public function Marks(){
        return $this->hasMany(Mark::class,'student_studying_information_id');
    }


}
