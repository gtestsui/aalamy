<?php

namespace Modules\LearningResource\Http\Requests\Topic;

use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic\MyOwnTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class GetMyOwnContentTopicIdRequest extends FormRequest
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

        //here should we check on topic id if we have a permission to make actions on them
        $topicClass = MyOwnTopicByAccountTypeManagementFactory::create($user);
        $topicClass->getMyTopicByIdOrFail($this->route('topic_id'));
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

            'content_type' => ['nullable',Rule::in(configFromModule('panel.topic_content_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME))],
            'topic_id' => 'required|exists:'.(new Topic())->getTable().',id',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

}
