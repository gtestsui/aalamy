<?php

namespace Modules\WorkSchedule\Http\Requests\WorkScheduleClass;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\WorkSchedule\Models\WorkScheduleClass;

class UpdateWorkScheduleClassRequest extends FormRequest
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

        $workScheduleClass = WorkScheduleClass::findOrFail($this->route('id'));

        $classInfo = ClassInfo::findOrFail($this->class_info_id);
        if($workScheduleClass->class_id != $classInfo->class_id){
            throw new ErrorUnAuthorizationException();
        }

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

            'class_info_id' => 'required',
            'week_day_id' => 'required',
            'period_number' => 'required|numeric|max:9',

//            'start' => 'required|date_format:'.config('panel.time_format'),
//            'end' => 'required|date_format:'.config('panel.time_format').'|after:start',

        ];
    }


    public function setWorkScheduleClass(WorkScheduleClass $workScheduleClass){
        $this->workScheduleClass = $workScheduleClass;
    }

    public function getWorkScheduleClass(){
        return $this->workScheduleClass;
    }


}
