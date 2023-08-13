<?php

namespace Modules\DiscussionCorner\Http\Requests\Post;

use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\DiscussionCorner\Http\Controllers\Classes\DiscussionCornerServices;
use Modules\DiscussionCorner\Models\DiscussionCornerPost;
use Modules\DiscussionCorner\Models\DiscussionCornerPostFile;
use Modules\DiscussionCorner\Models\DiscussionCornerPostPicture;
use Modules\DiscussionCorner\Models\DiscussionCornerPostVideo;
use Modules\DiscussionCorner\Traits\ValidationAttributesTrans;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;

class UpdatePostRequest extends FormRequest
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


    protected DiscussionCornerPost $post;

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
        $post = DiscussionCornerPost::findOrFail($this->route('id'));

        //to check if the user still has permission to this discussion
        $discussionClass  = DiscussionCornerServices::initializeManageDiscussionClass($post->educator_id,$post->school_id);

        $discussionClass->checkUpdatePost($user,$post);
        $this->setPost($post);

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
            'text' => 'required|string',

            'pictures' => 'array',
            'pictures.*' => 'image',
            'deleted_pictures' => 'array',
//            'deleted_pictures_ids.*' => 'exists:discussion_corner_post_pictures,id',
            'deleted_pictures_ids.*' => 'exists:'.(new DiscussionCornerPostPicture())->getTable().',id',

            'videos' => 'array',
            'videos.*' => 'file',
            'deleted_videos' => 'array',
//            'deleted_videos_ids.*' => 'exists:discussion_corner_post_videos,id',
            'deleted_videos_ids.*' => 'exists:'.(new DiscussionCornerPostVideo())->getTable().',id',

            'post_files' => 'array',
            'post_files.*' => 'required|file',
            'deleted_files' => 'array',
//            'deleted_files_ids.*' => 'exists:discussion_corner_post_files,id',
            'deleted_files_ids.*' => 'exists:'.(new DiscussionCornerPostFile())->getTable().',id',

//            'my_teacher_id' => 'nullable|exists:teachers,id',
            'my_teacher_id' => 'nullable|exists:'.(new Teacher())->getTable().',id',

        ];
    }

    public function setPost(DiscussionCornerPost $post){
        $this->post = $post;
    }

    public function getPost(){
        return $this->post;
    }
}
