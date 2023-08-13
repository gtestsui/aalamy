<?php

namespace Modules\Chat\Traits\ModelRelations;


use Modules\Chat\Models\Chat;

trait ChatMessageRelations
{

    //Relations
    public function Chat(){
        return $this->belongsTo(Chat::class,'chat_id');
    }



}
