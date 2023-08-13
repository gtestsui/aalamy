<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Survey;



use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;
use Modules\Roster\Models\Roster;

trait DiscussionCornerSurveyUserAnswerRelations
{

    //Relations
    public function SurveyUser(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerSurveyUser','survey_user_id');
    }

    public function Question(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion','question_id');
    }

    public function Choice(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice','choice_id');
    }



}
