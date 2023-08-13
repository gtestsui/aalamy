<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Survey;



use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;
use Modules\Roster\Models\Roster;

trait DiscussionCornerSurveyUserRelations
{

    //Relations
    public function Survey(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerSurvey','survey_id');
    }

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function UserAnswers(){
        return $this->hasMany(DiscussionCornerSurveyUserAnswer::class,'survey_user_id');
    }


}
