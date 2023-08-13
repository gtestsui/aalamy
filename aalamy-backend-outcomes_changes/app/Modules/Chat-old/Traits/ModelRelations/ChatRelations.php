<?php

namespace Modules\Chat\Traits\ModelRelations;


use Modules\Chat\Models\ChatMessage;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\User;

trait ChatRelations
{

    //Relations
    public function Parent(){
        return $this->belongsTo(ParentModel::class,'parent_id');
    }

    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Messages(){
        return $this->hasMany(ChatMessage::class,'chat_id');
    }


}
