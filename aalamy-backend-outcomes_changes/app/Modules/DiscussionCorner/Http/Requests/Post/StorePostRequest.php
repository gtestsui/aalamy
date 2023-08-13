<?php

namespace Modules\DiscussionCorner\Http\Requests\Post;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class StorePostRequest extends FormRequest
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
        if(isset($this->school_id)&&isset($this->educator_id))
            throw new ErrorMsgException('you cant send educator id with school id in same request');

        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($this->educator_id,$this->school_id);
        list($userAccountType,$userAccountObject) = UserServices::getAccountTypeAndObject($user,$this->my_teacher_id);

        $discussionClass->{'checkAddPostBy'.ucfirst($userAccountType)}($userAccountObject);


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
            'text' => 'required|string',
            'priority' => ['required',Rule::in(config('DiscussionCorner.panel.post_priority_values'))],
//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',
            'pictures' => 'array',
            'pictures.*' => 'image',
            'videos' => 'array',
            'videos.*' => 'file',
            'post_files' => 'array',
            'post_files.*' => 'required|file',
        ];
    }
}
