<?php

namespace Modules\LearningResource\Http\Requests\LearningResource;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\LearningResourceByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource\MyOwnLearningResourceByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicByAccountTypeManagementFactory;
use Modules\LearningResource\Models\LearningResource;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class DestroyLearningResourceRequest extends FormRequest
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

    protected LearningResource $learningResource;
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

        $learningResourceClass = MyOwnLearningResourceByAccountTypeManagementFactory::create($user);
        $learningResource = $learningResourceClass->getMyLearningResourceById($this->route('id'));
        if(is_null($learningResource))
            throw new ErrorUnAuthorizationException();
//
        $this->setLearningResource($learningResource);

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

    public function setLearningResource(LearningResource $learningResource){
        $this->learningResource = $learningResource;
    }

    public function getLearningResource(){
        return $this->learningResource;
    }
}
