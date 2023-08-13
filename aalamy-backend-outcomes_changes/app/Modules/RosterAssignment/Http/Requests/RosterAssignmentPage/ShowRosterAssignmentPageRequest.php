<?php

namespace Modules\RosterAssignment\Http\Requests\RosterAssignmentPage;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\RosterAssignmentManagementFactory;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignment\StudentRosterAssignment;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage\BaseRosterAssignmentPageAbstract;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage\RosterAssignmentPageManagementFactory;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage\StudentRosterAssignmentPage;
use Modules\RosterAssignment\Models\RosterAssignmentPage;
use Modules\RosterAssignment\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Student;
use Modules\User\Models\Teacher;

class ShowRosterAssignmentPageRequest extends FormRequest
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


    private BaseRosterAssignmentPageAbstract $rosterAssignmentPageClass;
    private RosterAssignmentPage $rosterAssignmentPage;

    private Student $student;
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
        UserServices::checkRoles($user,['educator','school','student','parent']);

        $rosterAssignmentClass = RosterAssignmentManagementFactory::create($user);
        $myRosterAssignment = $rosterAssignmentClass->myRosterAssignmentsByMyRostersByRosterAssignmentId($this->route('roster_assignment_id'));
        if(is_null($myRosterAssignment))
            throw new ErrorUnAuthorizationException();


        $rosterAssignmentPageClass = RosterAssignmentPageManagementFactory::create($user);
        $rosterAssignmentPage = $rosterAssignmentPageClass->getMyRosterAssignmentPageByRosterAssignemtIdByPageId($this->route('roster_assignment_id'),$this->route('page_id'));
        if(is_null($rosterAssignmentPage))
            throw new ErrorUnAuthorizationException();




        $this->setRosterAssignmentPageClass($rosterAssignmentPageClass);
        $this->setRosterAssignmentPage($rosterAssignmentPage);

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

            'student_id' => 'nullable',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }


    public function setRosterAssignmentPageClass(BaseRosterAssignmentPageAbstract $rosterAssignmentPageClass){
        $this->rosterAssignmentPageClass = $rosterAssignmentPageClass;
    }

    public function getRosterAssignmentPageClass(){
        return $this->rosterAssignmentPageClass;
    }



    public function setRosterAssignmentPage(RosterAssignmentPage $rosterAssignmentPage){
        $this->rosterAssignmentPage = $rosterAssignmentPage;
    }

    public function getRosterAssignmentPage(){
        return $this->rosterAssignmentPage;
    }


    public function setStudent(Student $student){
        $this->student = $student;
    }

    public function getStudent(){
        return $this->student;
    }


}
