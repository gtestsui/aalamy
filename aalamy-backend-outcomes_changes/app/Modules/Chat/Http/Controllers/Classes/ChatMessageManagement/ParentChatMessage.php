<?php

namespace Modules\Chat\Http\Controllers\Classes\ChatMessageManagement;

use Illuminate\Pagination\Paginator;
use Modules\Chat\Models\Chat;
use Modules\Chat\Models\ChatMessage;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\User;

class ParentChatMessage extends BaseChatMessageClassAbstract
{

    private $parentModel;

    public function __construct(ParentModel $parentModel){
        $this->parentModel = $parentModel;
    }

    public function getMyUserId(){
        return $this->parentModel->user_id;
    }




}