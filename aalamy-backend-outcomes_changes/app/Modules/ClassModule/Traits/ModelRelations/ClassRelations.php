<?php

namespace Modules\ClassModule\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;

trait ClassRelations
{

    //Relations
    public function Level(){
        return $this->belongsTo('Modules\Level\Models\Level','level_id');
    }

    public function ClassInfos(){
        return $this->hasMany('Modules\ClassModule\Models\ClassInfo','class_id');
    }

    public function ClassStudents(){
        return $this->hasMany(ClassStudent::class,'class_id');

    }

}
