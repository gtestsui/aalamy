<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Survey;



use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;
use Modules\Roster\Models\Roster;

trait DiscussionCornerSurveyQuestionChoiceRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestion','question_id');
    }

    public function SurveyUserAnswers(){
        return $this->hasMany(DiscussionCornerSurveyUserAnswer::class,'choice_id');
    }
    
    public function LimitedSurveyUserAnswers(){
        return $this->SurveyUserAnswers()
            ->limit(config('DiscussionCorner.panel.limited_survey_user_answer_count_per_question'));
    }


}
