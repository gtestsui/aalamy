<?php

namespace Modules\FlashCard\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\FlashCard\Models\FlashCard;
use Modules\Roster\Models\Roster;
use Modules\User\Models\Student;

trait FlashCardQuizStudentPercentageRelations
{

    //Relations
    public function FlashCard(){
        return $this->belongsTo(FlashCard::class,'flash_card_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class,'student_id');

    }



}
