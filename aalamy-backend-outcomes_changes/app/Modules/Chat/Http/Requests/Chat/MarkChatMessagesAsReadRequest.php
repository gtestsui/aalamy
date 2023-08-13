<?php

namespace Modules\Chat\Http\Requests\Chat;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Chat\Http\Controllers\Classes\ChatManagement\ChatManagementFactory;
use Modules\Chat\Models\Chat;
use Modules\ContactUs\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class MarkChatMessagesAsReadRequest extends FormRequest
{
    /**
     * @uses ResponseValidationFormRequest it is responsible to return validation
     * messages error as json
     * @uses AuthorizesAfterValidation it is responsible to call authorizeValidated
     * after check on validation rules
     * @uses ValidationAttributesTrans it is responsible to translate the parameters
     * in rule array
     */
    use ResponseValidationFormRequest,AuthorizesAfterValidation,ValidationAttributesTrans;


    private Chat $chat;
    /**
     * Customized authorization from AuthorizesAfterValidation Trait
     * to check authorize after validation
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorizeAfterValidate()
    {
        $user = $this->user();
        UserServices::checkRoles($user,['parent','school']);

        $chatManagement = ChatManagementFactory::create($user);
        $chat = $chatManagement->getMyChatById($this->route('chat_id'));
        if(is_null($chat)){
            throw new ErrorUnAuthorizationException();
        }

        $this->setChat($chat);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [


        ];
    }


    public function setChat(Chat $chat){
        $this->chat = $chat;
    }

    public function getChat(){
        return $this->chat;
    }

}
