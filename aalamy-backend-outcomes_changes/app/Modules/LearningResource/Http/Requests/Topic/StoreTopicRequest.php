<?php

namespace Modules\LearningResource\Http\Requests\Topic;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Traits\ValidationAttributesTrans;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyAllwoedTopic\MyAllowedTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\MyOwnTopic\MyOwnTopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageTopicByAccountType\TopicConstants;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class StoreTopicRequest extends FormRequest
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

//    private ?Topic $parentTopic=null;
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
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user,true);

        if(!is_null($this->topic_id))
            $topic = LearningResourceServices::checkUseTopic($user,$this->topic_id);

//        if(!is_null($this->topic_id)){
//            $topiceClass  = MyOwnTopicByAccountTypeManagementFactory::createByAccountTypeAndObject($accountType,$accountObject/*,$user*/);
//            $parentTopic = $topiceClass->getMyTopicById($this->topic_id);
//            if(is_null($parentTopic)){
//                //check if the parent topic it's from my school and i have access to write
//                if($user->account_type != 'educator')
//                    throw new ErrorUnAuthorizationException();
//                $topicClass  = MyAllowedTopicByAccountTypeManagementFactory::createByAccountTypeAndObject($accountType,$accountObject/*,$user*/);
//                $parentTopic = $topicClass->changeAccessType(TopicConstants::WRITE_ACCESS_TYPE)
//                    ->getMyAllowedTopicById($this->topic_id);
//                if(is_null($parentTopic))
//                    throw new ErrorUnAuthorizationException();
//            }
//        }

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

            'topic_id' => 'nullable|exists:'.(new Topic())->getTable().',id',
            'name' => 'required',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

//    public function setParentTopic(Topic $parentTopic){
//        $this->parentTopic = $parentTopic;
//    }
//
//    public function getParentTopic(){
//        return $this->parentTopic;
//    }

}
