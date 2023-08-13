<?php

namespace Modules\FlashCard\Traits\ModelRelations;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Event\Models\EventTargetUser;
use Modules\Roster\Models\Roster;

trait CardRelations
{

    //Relations
    public function FlashCard(){
        return $this->belongsTo('Modules\FlashCard\Models\FlashCard','flash_card_id');
    }


}
