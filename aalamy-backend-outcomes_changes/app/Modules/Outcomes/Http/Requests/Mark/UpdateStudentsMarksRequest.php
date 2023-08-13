<?php

namespace Modules\Outcomes\Http\Requests\Mark;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\ClassManagementFactory;
use Modules\ClassModule\Models\ClassModel;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\Level\Http\Controllers\Classes\ManageSubject\SubjectManagementFactory;
use Modules\Level\Models\BaseLevelSubject;
use Modules\Level\Traits\ValidationAttributesTrans;
use Modules\Outcomes\Models\Mark;
use Modules\Outcomes\Models\StudentStudyingInformation;
use Modules\User\Http\Controllers\Classes\UserServices;

class UpdateStudentsMarksRequest extends FormRequest
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


    protected Mark $mark;

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
        UserServices::checkRoles($user,['school','teacher']);

        $mark = Mark::findOrFail($this->route('mark_id'));
        $studentStudyingInformation = StudentStudyingInformation::findOrFail($mark->student_studying_information_id);

        $subjectManagament = SubjectManagementFactory::create($user);
        $subject = $subjectManagament->mySubjectById($mark->subject_id);

        $levelManagament = LevelManagementFactory::create($user);
        $level = $levelManagament->myLevelsById($studentStudyingInformation->level_id);


        if(is_null($level) || is_null($subject)){
            throw new ErrorUnAuthorizationException();
        }

        //validation on degree
        $baseLevelSubject = BaseLevelSubject::where('base_level_id',$level->base_level_id)
            ->where('base_subject_id',$subject->base_subject_id)
            ->with('Rule')
            ->first();
        $maxDegree = $baseLevelSubject->Rule->max_degree;
        $maxVerbalDegree = $maxDegree;
        $maxJobsAndWorksheetsDegree = $maxDegree;
        $maxActivitiesAndInitiativesDegree = $maxDegree;
        $maxQuizDegree = $maxDegree;
        $maxExamDegree = $maxDegree;
        if(
            $this->verbal>$maxVerbalDegree ||
            $this->jobs_and_worksheets>$maxJobsAndWorksheetsDegree ||
            $this->activities_and_Initiatives>$maxActivitiesAndInitiativesDegree ||
            $this->quiz>$maxQuizDegree ||
            $this->exam>$maxExamDegree
        ){
            throw new ErrorMsgException('please look to the max degree of your fields');
        }

        $this->setMark($mark);
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
            'verbal' => 'nullable|numeric',
            'jobs_and_worksheets' => 'nullable|numeric',
            'activities_and_Initiatives' => 'nullable|numeric',
            'quiz' => 'nullable|numeric',
            'exam' => 'nullable|numeric',
        ];
    }


    public function setMark(Mark $mark){
        $this->mark = $mark;
    }

    /**
     * @return Mark
     */
    public function getMark(){
        return $this->mark;
    }

}
