<?php

namespace Modules\Event\Http\Requests\Event;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Event\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\ParentModel;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class StoreEventRequest extends FormRequest
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
            'all_parents' => 'nullable|boolean',
            'parent_ids' => 'array',
            'parent_ids.*' => 'exists:'.(new ParentModel())->getTable().',id',
            'all_students' => 'nullable|boolean',
            'student_ids' => 'array',
            'student_ids.*' => 'exists:'.(new Student())->getTable().',id',
            //this array its just if my account type is school
            //because  just the school can add teacher as targets
            'all_teachers' => 'nullable|boolean',
            'teacher_ids' => 'array',
            'teacher_ids.*' => 'exists:'.(new Teacher())->getTable().',id',
            'name' => 'required|string',
            'date' => 'required|date_format:'.config('panel.date_format').' '.config('panel.time_format'),
//            'time' => 'required|date_format:'.config('panel.time_format'),

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }
}
