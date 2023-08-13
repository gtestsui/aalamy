<?php

namespace Modules\Mark\Http\Requests\GradeBook;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Mark\Http\Controllers\Classes\ManageGradeBook\GradeBookManagementFactory;
use Modules\Mark\Models\GradeBook;
use Modules\Roster\Http\Controllers\Classes\ManageRoster\RosterManagementFactory;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class ShowGradeBookRequest extends FormRequest
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

    private GradeBook $gradeBook;
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
        $gradeBookClass = GradeBookManagementFactory::create($user);
        $gradeBook = $gradeBookClass->getMyGradeBookById($this->route('grade_book_id'));
        if(is_null($gradeBook))
            throw new ErrorUnAuthorizationException();

        $this->setGradeBook($gradeBook);

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


//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setGradeBook(GradeBook $gradeBook){
        $this->gradeBook = $gradeBook;
    }

    public function getGradeBook(){
        return $this->gradeBook;
    }


}
