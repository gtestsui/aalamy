<?php

namespace Modules\LearningResource\Traits\ModelRelations\Topic;


use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

trait TopicRelations
{

    //Relations
    public function Parent(){
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

    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function Topics(){
        return $this->hasMany(Topic::class,'topic_id');
    }

    public function AllRelatedChildTopics(){
        return $this->hasMany(Topic::class,'topic_id')->with('AllRelatedChildTopics');
    }


    public function LearningResources(){
        return $this->hasMany(LearningResource::class,'topic_id');
    }



}
