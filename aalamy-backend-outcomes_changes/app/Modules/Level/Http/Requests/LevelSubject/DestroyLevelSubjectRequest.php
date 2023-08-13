<?php

namespace Modules\Level\Http\Requests\LevelSubject;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;

class DestroyLevelSubjectRequest extends FormRequest
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

    protected $levelSubject;
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
        $levelSubject = LevelSubject::findOrFail($this->route('id'));
        LevelServices::checkOwnerLevelSubjectAuthorization($user,$levelSubject);
        $this->setLevelSubject($levelSubject);
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

    public function setLevelSubject($levelSubject){
        $this->levelSubject = $levelSubject;
    }


    public function getLevelSubject(){
        return $this->levelSubject;
    }

}
