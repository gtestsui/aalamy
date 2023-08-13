<?php

namespace Modules\DiscussionCorner\Http\Requests\Reply;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;

class StoreReplyRequest extends FormRequest
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
        $post = DiscussionCornerPost::findOrFail($this->post_id);
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
//            'post_id' => 'required|exists:discussion_corner_posts,id',
            'post_id' => 'required|exists:'.(new DiscussionCornerPost())->getTable().',id',
            'text' => 'nullable|string',
            'picture' => 'nullable|image',
        ];
    }
}
