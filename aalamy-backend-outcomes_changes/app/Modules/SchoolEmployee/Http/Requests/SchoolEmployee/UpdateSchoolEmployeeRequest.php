<?php

namespace Modules\SchoolEmployee\Http\Requests\SchoolEmployee;

use App\Exceptions\ErrorMsgException;
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
use Modules\SchoolEmployee\Models\SchoolEmployee;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\Requests\Register\BaseRegisterRequest;

class UpdateSchoolEmployeeRequest extends FormRequest
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


    private SchoolEmployee $schoolEmployee;

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
        $user->load('School');
        $employee = SchoolEmployee::my($user->School->id)
            ->findOrFail($this->route('id'));
        $this->setSchoolEmployee($employee);
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
            'phone_number' => 'nullable|unique:school_employees,phone_number,'.$this->route('id'),
            'phone_code' => 'required_with:phone_number|nullable',
            'phone_iso_code' => 'required_with:phone_number|nullable',
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
            'certificates_ids_for_delete' => 'nullable|array',

        ];

        $baseRules = [];
        if($this->type == 'teacher'){
            $baseRules  = (new BaseRegisterRequest())->rules();
//            $baseRules ['device_type'] = 'nullable';
            $baseRules ['email'] = 'nullable';
            $baseRules ['firebase_token'] = 'nullable';
            $baseRules ['password'] = 'nullable|min:6|confirmed';
            $baseRules ['password_confirmation'] = 'required_with:password';

            $baseRules ['phone_number'] = 'nullable|unique:users,phone_number';

        }

        return array_merge($baseRules,$myRules);

    }


    public function getSchoolEmployee(){
        return $this->schoolEmployee;
    }

    public function setSchoolEmployee(SchoolEmployee $schoolEmployee){
        $this->schoolEmployee = $schoolEmployee;
    }



}
