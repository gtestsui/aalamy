<?php

namespace Modules\ContactUs\Traits\ModelRelations;


use Modules\User\Models\User;

trait ContactUsRelations
{

    //Relations
    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }


}
