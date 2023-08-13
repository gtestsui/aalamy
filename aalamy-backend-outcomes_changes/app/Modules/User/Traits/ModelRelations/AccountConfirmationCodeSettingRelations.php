<?php

namespace Modules\User\Traits\ModelRelations;

use Modules\User\Models\User;

trait AccountConfirmationCodeSettingRelations
{

    //Relations
    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

}
