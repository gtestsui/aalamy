<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Survey;



use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;

trait DiscussionCornerSurveyRelations
{

    //Relations
    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }


    public function SurveyQuestions(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion','survey_id');
    }

    //users had answered the survey
    public function SurveyUsers(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser','survey_id');
    }


}
