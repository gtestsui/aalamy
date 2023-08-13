<?php

namespace Modules\Chat\Http\Requests\ChatMessage;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Chat\Http\Controllers\Classes\ChatManagement\ChatManagementFactory;
use Modules\Chat\Http\Controllers\Classes\ChatMessageManagement\ChatMessageManagementFactory;
use Modules\Chat\Models\Chat;
use Modules\Chat\Models\ChatMessage;
use Modules\ContactUs\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class DestroyChatMessageRequest extends FormRequest
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


    private ChatMessage $chatMessage;
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

        $chatMessageManagement = ChatMessageManagementFactory::create($user);
        $chatMessage = $chatMessageManagement->getMessageById($this->route('id'));
//        $chatMessage = $chatMessageManagement->getMessageByIdAndChatId($this->route('chat_id'),$this->route('id'));
        if(is_null($chatMessage)){
            throw new ErrorUnAuthorizationException();
        }

        $this->setChatMessage($chatMessage);

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


    public function setChatMessage(ChatMessage $chatMessage){
        $this->chatMessage = $chatMessage;
    }

    public function getChatMessage(){
        return $this->chatMessage;
    }

}
