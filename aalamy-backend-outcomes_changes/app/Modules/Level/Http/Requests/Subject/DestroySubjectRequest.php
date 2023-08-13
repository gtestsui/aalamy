<?php

namespace Modules\Level\Http\Requests\Subject;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Subject;

class DestroySubjectRequest extends FormRequest
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

    protected $level;
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
        $subject = Subject::findOrFail($this->route('id'));
        LevelServices::checkOwnerSubjectAuthorization($user,$subject);
        $this->setSubject($subject);
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

    public function getSubject(){
        return $this->subject;
    }

    public function setSubject(Subject $subject){
        $this->subject = $subject;
    }
}
