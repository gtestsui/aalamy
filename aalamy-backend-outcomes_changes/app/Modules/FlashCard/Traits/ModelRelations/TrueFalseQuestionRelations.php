<?php

namespace Modules\FlashCard\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait TrueFalseQuestionRelations
{

    //Relations
    public function FlashCard(){
        return $this->belongsTo('Modules\FlashCard\Models\FlashCard','flash_card_id');
    }

    public function QuestionCard(){
        return $this->belongsTo('Modules\FlashCard\Models\Card','question_card_id');
    }

    public function AnswerCard(){
        return $this->belongsTo('Modules\FlashCard\Models\Card','answer_card_id');
    }


}
