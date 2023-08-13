<?php

namespace Modules\FlashCard\Http\Requests\Card;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assignment\Models\Assignment;
use Modules\FlashCard\Http\Controllers\Classes\FlashCardServices;
use Modules\FlashCard\Models\Card;
use Modules\FlashCard\Models\FlashCard;
use Modules\FlashCard\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class DestroyCardRequest extends FormRequest
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

    private Card $card;

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
        UserServices::checkRoles($user,['educator','school']);

        $card = Card::findOrFail($this->route('id'));

        $flashCard = FlashCard::findOrFail($card->flash_card_id);

        FlashCardServices::checkUseFlashCard($flashCard,$user,$this->my_teacher_id);

        $this->setCard($card);

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

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setCard(Card $card){
        $this->card = $card;
    }

    public function getCard(){
        return $this->card;
    }
}
