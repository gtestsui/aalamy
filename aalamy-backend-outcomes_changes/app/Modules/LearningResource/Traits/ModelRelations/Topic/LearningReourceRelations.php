<?php

namespace Modules\LearningResource\Traits\ModelRelations\Topic;


use Modules\LearningResource\Models\Topic;
use Modules\Level\Models\Lesson;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Unit;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait LearningReourceRelations
{

    //Relations
    public function Topic(){
        return $this->belongsTo(Topic::class,'topic_id');
    }

    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }

    public function LevelSubject(){
        return $this->belongsTo(LevelSubject::class,'level_subject_id');
    }

    public function Unit(){
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function Lesson(){
        return $this->belongsTo(Lesson::class,'lesson_id');
    }


}
