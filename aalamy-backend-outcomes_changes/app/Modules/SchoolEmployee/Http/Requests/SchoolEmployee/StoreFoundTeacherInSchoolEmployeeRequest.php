<?php

namespace Modules\SchoolEmployee\Http\Requests\SchoolEmployee;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class StoreFoundTeacherInSchoolEmployeeRequest extends FormRequest
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


    private Teacher $teacher;


    /**
     * we have initialized the teacher here because we need teacher object in the rules method for unique rules
     */
    public function __construct()
    {
        $teacher = Teacher::find(request()->teacher_id);
        $this->setTeacher($teacher);
    }

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
        UserServices::checkRoles($user,['school']);
        list(,$school) = UserServices::getAccountTypeAndObject($user);
//        $teacher = Teacher::find($this->teacher_id);
        if($this->teacher->school_id != $school->id){
            throw new ErrorUnAuthorizationException();
        }
//        $this->setTeacher($teacher);

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
            'teacher_id' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'grandfather_name' => 'required',
            'date_of_birth' => 'required|date_format:'.config('panel.date_format'),
            'gender' => ['required',Rule::in(['male','female'])],
            'place_of_birth' => 'required',
            'phone_number' => 'required|unique:users,phone_number,'.$this->teacher->user_id,
            'phone_code' => 'required',
            'phone_iso_code' => 'required',
            'original_state' => ['required',Rule::in(configFromModule('panel.original_states',ApplicationModules::SCHOOL_EMPLOYEE_MODULE_NAME))],
            'place_of_registration' => 'required',
            'number_of_registration' => 'required',
            'nationality' => ['required',Rule::in(configFromModule('panel.employee_nationalities',ApplicationModules::SCHOOL_EMPLOYEE_MODULE_NAME))],
            'identifier_number' => 'required',
            'address' => 'required',
            'marriage_state' => ['required',Rule::in([
                'married',//مؤهل
                'unmarried'//اعزب
            ])],
            'job_info' => 'nullable',
            'experience' => 'nullable',
            'computer_skills' => 'nullable',
            'type' => ['required',Rule::in(configFromModule('panel.employee_types',ApplicationModules::SCHOOL_EMPLOYEE_MODULE_NAME))],
            'certificates_images' => 'nullable|array',
            'certificates_images.*' => 'nullable|image',

            'certificates_files' => 'nullable|array',
            'certificates_files.*' => 'nullable|file',
        ];


    }



    public function setTeacher(Teacher $teacher){
        $this->teacher = $teacher;
    }

    public function getTeacher(){
        return $this->teacher;
    }


}
