<?php

namespace Modules\Notification\Http\Requests;

use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Roster\Traits\ValidationAttributesTrans;
use Modules\SubscriptionPlan\Http\Controllers\Classes\PlanConstraints\Factory\PlanConstraintsManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class SendManualNotificationRequest extends FormRequest
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

        $manualNotificationModuleClass = PlanConstraintsManagementFactory::createManualNotificationModule($user,$this->my_teacher_id);
        $manualNotificationModuleClass->check();

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
            'notification_subject' => 'required|string',
            'notification_content' => 'required|string',
            'priority' => ['required',Rule::in(configFromModule('panel.manual_notification_priority',ApplicationModules::NOTIFICATION_MODULE_NAME))],
            'send_by_types' => 'required|array',
            'send_by_types.*' => ['required',Rule::in(config('Notification.panel.send_by_types'))],

            'parent_ids' => 'array',
//            'parent_ids.*' => 'exists:parents,id',
            'parent_ids.*' => 'exists:'.(new ParentModel())->getTable().',id',
            'student_ids' => 'array',
//            'student_ids.*' => 'exists:students,id',
            'student_ids.*' => 'exists:'.(new Student())->getTable().',id',
            //this array its just if my account type is school
            //because  just the school can send notification for teachers
            'teacher_ids' => 'array',
//            'teacher_ids.*' => 'exists:teachers,id',
            'teacher_ids.*' => 'exists:'.(new Teacher())->getTable().',id',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

            'all_parents' => 'nullable|boolean',
            'all_students' => 'nullable|boolean',
            'all_teachers' => 'nullable|boolean',


        ];
    }
}
