<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Survey;



use Modules\ClassModule\Models\ClassStudent;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyQuestionChoice;
use Modules\DiscussionCorner\Models\DiscussionCornerSurveyUserAnswer;
use Modules\Roster\Models\Roster;

trait DiscussionCornerSurveyQuestionRelations
{

    //Relations
    public function Survey(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerSurvey','survey_id');
    }

    public function Choices(){
        return $this->hasMany(DiscussionCornerSurveyQuestionChoice::class,'question_id');
    }

    public function SurveyUserAnswers(){
        return $this->hasMany(DiscussionCornerSurveyUserAnswer::class,'question_id');
    }

    public function LimitedSurveyUserAnswers(){
        return $this->SurveyUserAnswers()
            ->whereNull('choice_id')
            ->limit(config('DiscussionCorner.panel.limited_survey_user_answer_count_per_question'));
    }



}
