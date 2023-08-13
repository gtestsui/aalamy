<?php

namespace Modules\DiscussionCorner\Http\Requests\Reply;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class DisplayReplyRequest extends FormRequest
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
        $post = DiscussionCornerPost::findOrFail($this->route('postId'));
        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($post->educator_id,$post->school_id);
        list($userAccountType,$userAccountObject) = UserServices::getAccountTypeAndObject($user,$this->my_teacher_id);

        $discussionClass->{'checkReplyOnPostBy'.ucfirst($userAccountType)}($userAccountObject);


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
}
