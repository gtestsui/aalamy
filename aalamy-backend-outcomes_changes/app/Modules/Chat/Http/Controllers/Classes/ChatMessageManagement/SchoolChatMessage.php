<?php

namespace Modules\Chat\Http\Controllers\Classes\ChatMessageManagement;

use Modules\Chat\Models\Chat;
use Modules\Chat\Models\ChatMessage;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\User;

class SchoolChatMessage extends BaseChatMessageClassAbstract
{

    private $school;

    public function __construct(School $school){
        $this->school = $school;
    }


    public function getMyUserId(){
        return $this->school->user_id;
    }

}