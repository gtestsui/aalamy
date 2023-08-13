<?php

namespace Modules\Roster\Http\Requests\Roster;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Models\LevelSubject;
use Modules\Roster\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\RosterCountModuleClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreRosterForEducatorRequest extends FormRequest
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


    protected LevelSubject $levelSubject;
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
        UserServices::checkRoles($user,['educator']);

        if (!is_null($this->my_teacher_id))//just the educator who has this privilege
            throw new ErrorUnAuthorizationException();

        $levelManagment = LevelManagementFactory::create($user);
        $levelSubject = $levelManagment->myLevelSubjectById($this->level_subject_id);
        if(is_null($levelSubject))
            throw new ErrorUnAuthorizationException();

        $assignmentCountModuleClass = RosterCountModuleClass::createByOwner($user);
        $assignmentCountModuleClass->check();


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
//            'class_info_id' => 'required|exists:'.(new ClassInfo())->getTable().',id',
            'level_subject_id' => 'required',
            'name' => 'required|string',
            'description' => 'required|string',
            'color' => 'required|string',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }


    public function setLevelSubject(LevelSubject $levelSubject){
        $this->levelSubject = $levelSubject;
    }

    public function getLevelSubject(){
        return $this->levelSubject;
    }

}
