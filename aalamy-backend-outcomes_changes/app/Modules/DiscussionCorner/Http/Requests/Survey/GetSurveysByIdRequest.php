<?php

namespace Modules\DiscussionCorner\Http\Requests\Survey;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner\ManageDiscussionCorner;
use Modules\DiscussionCorner\Models\DiscussionCornerSurvey;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class GetSurveysByIdRequest extends FormRequest
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

//    protected ManageDiscussionCorner $discussionClass;
    protected DiscussionCornerSurvey $survey;
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
        $survey = DiscussionCornerSurvey::findOrFail($this->route('id'));

        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($survey->educator_id,$survey->school_id);
        list($userAccountType,$userAccountObject) = UserServices::getAccountTypeAndObject($user,$this->my_teacher_id);

        $discussionClass->{'checkDisplayPostsBy'.ucfirst($userAccountType)}($userAccountObject);
//        $this->setDiscussionClass($discussionClass);
        $this->setSurvey($survey);

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
        ];
    }

    public function setSurvey(DiscussionCornerSurvey $survey){
        $this->survey = $survey;
    }

    public function getSurvey(){
        return $this->survey;
    }
}
