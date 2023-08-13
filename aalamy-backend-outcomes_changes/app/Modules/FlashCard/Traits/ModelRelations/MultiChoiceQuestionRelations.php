<?php

namespace Modules\FlashCard\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait MultiChoiceQuestionRelations
{

    //Relations
    public function FlashCard(){
        return $this->belongsTo('Modules\FlashCard\Models\FlashCard','flash_card_id');
    }

    public function Card(){
        return $this->belongsTo('Modules\FlashCard\Models\Card','card_id');
    }

    public function Choices(){
        return $this->hasMany('Modules\FlashCard\Models\MultiChoiceChoice','multi_choice_question_id');
    }



}
