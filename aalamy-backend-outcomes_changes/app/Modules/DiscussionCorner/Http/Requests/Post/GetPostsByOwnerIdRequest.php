<?php

namespace Modules\DiscussionCorner\Http\Requests\Post;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Http\Controllers\Classes\ManageDiscussionCorner\ManageByCornerOwner\ManageDiscussionCorner;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;

class GetPostsByOwnerIdRequest extends FormRequest
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

    protected ManageDiscussionCorner $discussionClass;
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
        if(isset($this->school_id)&&isset($this->educator_id))
            throw new ErrorMsgException('you cant send educator id with school id in same request');

        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($this->educator_id,$this->school_id);
        list($userAccountType,$userAccountObject) = UserServices::getAccountTypeAndObject($user,$this->my_teacher_id);

        $discussionClass->{'checkDisplayPostsBy'.ucfirst($userAccountType)}($userAccountObject);
        $this->setDiscussionClass($discussionClass);

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
//            'school_id' => 'required_without:educator_id|exists:schools,id',
            'school_id' => 'required_without:educator_id|exists:'.(new School())->getTable().',id',
//            'educator_id' => 'required_without:school_id|exists:educators,id',
            'educator_id' => 'required_without:school_id|exists:'.(new Educator())->getTable().',id',
        ];
    }

    public function setDiscussionClass(ManageDiscussionCorner $discussionClass){
        $this->discussionClass = $discussionClass;
    }

    public function getDiscussionClass(){
        return $this->discussionClass;
    }
}
