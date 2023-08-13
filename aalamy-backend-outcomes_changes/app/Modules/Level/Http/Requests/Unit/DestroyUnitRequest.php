<?php

namespace Modules\Level\Http\Requests\Unit;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;

class DestroyUnitRequest extends FormRequest
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

    protected  $unit;
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
        UserServices::checkRoles($user,config('Level.panel.owners_unit_type'));
        $unit = Unit::findOrFail($this->route('id'));

        //we use teacher id to know if the educator is teacher or is educator
//        LevelServices::checkOwnerUnitAuthorization($user,$unit,$this->my_teacher_id);
        LevelServices::checkDestroyUnitAuthorization($user,$unit,$this->my_teacher_id);

        $this->setUnit($unit);
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

    public function setUnit(Unit $unit){
        $this->unit = $unit;
    }

    public function getUnit(){
        return $this->unit;
    }
}
