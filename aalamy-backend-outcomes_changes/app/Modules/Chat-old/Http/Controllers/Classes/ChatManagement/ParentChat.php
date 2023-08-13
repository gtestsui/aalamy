<?php

namespace Modules\Chat\Http\Controllers\Classes\ChatManagement;

use Modules\User\Models\ParentModel;

class ParentChat extends BaseChatClassAbstract
{

    private $parentModel;

    public function __construct(ParentModel $parentModel){
        $this->parentModel = $parentModel;
    }


    public function getMyUserId(){
        return $this->parentModel->user_id;
    }



}