<?php

namespace Modules\FlashCard\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait MultiChoiceChoiceRelations
{

    //Relations
    public function Question(){
        return $this->belongsTo('Modules\FlashCard\Models\MultiChoiceQuestion','multi_choice_question_id');
    }

    public function Card(){
        return $this->belongsTo('Modules\FlashCard\Models\Card','card_id');
    }


}
