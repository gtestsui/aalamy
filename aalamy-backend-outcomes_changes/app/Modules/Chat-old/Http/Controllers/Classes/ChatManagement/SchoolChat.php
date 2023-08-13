<?php

namespace Modules\Chat\Http\Controllers\Classes\ChatManagement;

use Modules\User\Models\School;

class SchoolChat extends BaseChatClassAbstract
{

    private $school;

    public function __construct(School $school){
        $this->school = $school;
    }

    public function getMyUserId(){
        return $this->school->user_id;
    }


}