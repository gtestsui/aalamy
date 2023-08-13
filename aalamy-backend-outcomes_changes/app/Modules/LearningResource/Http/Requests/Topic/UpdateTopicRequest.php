<?php

namespace Modules\LearningResource\Http\Requests\Topic;

use App\Exceptions\ErrorUnAuthorizationException;
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

class UpdateTopicRequest extends FormRequest
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

    protected Topic $topic;
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

        $topiceClass  = MyOwnTopicByAccountTypeManagementFactory::create($user);
        $topic = $topiceClass->getMyTopicById($this->route('id'));
        if(is_null($topic))
            throw new ErrorUnAuthorizationException();

        $this->setTopic($topic);

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


            'read_share_type' => ['required',Rule::in(configFromModule('panel.learning_resource_read_share_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME))],
            'write_share_type' => ['nullable',Rule::in(configFromModule('panel.learning_resource_write_share_types',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME))],

            'name' => 'required',
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setTopic(Topic $topic){
        $this->topic = $topic;
    }

    public function getTopic(){
        return $this->topic;
    }
}
