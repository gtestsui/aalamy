<?php

namespace Modules\Level\Http\Requests\Unit;

use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;
use Modules\Level\Models\Unit;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdateUnitRequest extends FormRequest
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
        $levelSubject = LevelSubject::/*with('Subject')->*/findOrFail($this->level_subject_id);
//        $subject = Subject::findOrFail($levelSubject->subject_id);
        $unit = Unit::findOrFail($this->route('id'));

        //we use teacher id to know if the educator is teacher or is educator
//        LevelServices::checkOwnerUnitAuthorization($user,$unit,$this->my_teacher_id);
        LevelServices::checkUpdateUnitAuthorization($user,$unit,$this->my_teacher_id);

        //if the level_subject_id changed then we need to check if the new one its belong to user who want to update
        if($unit->level_subject_id != $levelSubject->id)
            LevelServices::checkAddUnitAuthorization($user,$levelSubject,$this->my_teacher_id);


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
//            'level_subject_id' => 'required|exists:level_subjects,id',
            'level_subject_id' => 'required|exists:'.(new LevelSubject())->getTable().',id',
            'name'       => 'required',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setUnit(Unit $unit){
        $this->unit = $unit;
    }

    public function getUnit(){
        return $this->unit;
    }
}
