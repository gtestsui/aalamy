<?php

namespace Modules\FlashCard\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait FlashCardRelations
{

    //Relations
    public function Assignment(){
        return $this->belongsTo('Modules\Assignment\Models\Assignment','assignment_id');
    }

    public function Cards(){
        return $this->hasMany('Modules\FlashCard\Models\Card','flash_card_id');
    }

    public function MultiChoiceQuestions(){
        return $this->hasMany('Modules\FlashCard\Models\MultiChoiceQuestion','flash_card_id');
    }

    public function TrueFalseQuestions(){
        return $this->hasMany('Modules\FlashCard\Models\TrueFalseQuestion','flash_card_id');
    }



}
