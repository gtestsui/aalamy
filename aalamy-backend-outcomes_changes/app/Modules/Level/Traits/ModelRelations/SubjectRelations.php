<?php

namespace Modules\Level\Traits\ModelRelations;


use Modules\Assignment\Models\Assignment;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Models\BaseSubject;
use Modules\Level\Models\LevelSubject;

trait SubjectRelations
{

    //Relations
    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function LevelSubjects(){
        return $this->hasMany(LevelSubject::class,'subject_id');
    }

    public function BaseSubject(){
        return $this->belongsTo(BaseSubject::class,'base_subject_id');
    }

}
