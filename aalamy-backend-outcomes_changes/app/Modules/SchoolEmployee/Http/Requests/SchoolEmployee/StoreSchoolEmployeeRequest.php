<?php

namespace Modules\SchoolEmployee\Http\Requests\SchoolEmployee;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SubjectManagementFactory;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\Register\BaseRegisterRequest;

class StoreSchoolEmployeeRequest extends FormRequest
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
        UserServices::checkRoles($user,['school']);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $myRules = [
            'fname' => 'required',
            'lname' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'grandfather_name' => 'required',
            'date_of_birth' => 'required|date_format:'.config('panel.date_format'),
            'gender' => ['required',Rule::in(['male','female'])],
            'place_of_birth' => 'required',
            'phone_number' => 'required|unique:users,phone_number',
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

        $baseRules = [];
        if($this->type == 'teacher'){
            $baseRules  = (new BaseRegisterRequest())->rules();
//            $baseRules ['device_type'] = 'nullable';
            $baseRules ['firebase_token'] = 'nullable';
            $baseRules ['password'] = 'required|min:6|confirmed';
            $baseRules ['password_confirmation'] = 'required_with:password';
        }
        return array_merge($myRules,$baseRules);

    }



}
