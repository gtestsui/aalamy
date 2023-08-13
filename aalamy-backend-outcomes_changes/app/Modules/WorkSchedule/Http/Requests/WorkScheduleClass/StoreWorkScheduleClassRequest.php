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

class StoreWorkScheduleClassRequest extends FormRequest
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

        $classManagement = ClassManagementFactory::create($user);
        $classModel = $classManagement->myClassesByIdOrFail($this->route('class_id'));

        if(!isset($this->delete_it) || !$this->delete_it){
       
        	$classInfo = ClassInfo::findOrFail($this->class_info_id);
            if($classModel->id != $classInfo->class_id){
                throw new ErrorUnAuthorizationException();
            }
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

            'class_info_id' => 'required_without:delete_it',
            'week_day_id' => 'required',
            'period_number' => 'required|numeric|max:9',
            'delete_it' => 'required_without:class_info_id',
//            'start' => 'required|date_format:'.config('panel.time_format'),
//            'end' => 'required|date_format:'.config('panel.time_format').'|after:start',

        ];
    }


}
