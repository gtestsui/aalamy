<?php

namespace Modules\Level\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Models\BaseLevel;
use Modules\Level\Models\LevelSubject;
use Modules\Outcomes\Models\StudentStudyingInformation;

trait LevelRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function Classes(){
        return $this->hasMany(ClassModel::class,'level_id');
    }

    public function LevelSubjects(){
        return $this->hasMany(LevelSubject::class,'level_id');
    }

    public function BaseLevel(){
        return $this->belongsTo(BaseLevel::class,'base_level_id');
    }


    public function StudentStudyingInformation(){
        return $this->hasMany(StudentStudyingInformation::class,'level_id');
    }


}
