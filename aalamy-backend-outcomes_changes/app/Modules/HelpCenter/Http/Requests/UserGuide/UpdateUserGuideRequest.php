<?php

namespace Modules\HelpCenter\Http\Requests\UserGuide;

use App\Http\Traits\AuthorizesAfterValidation;
use App\Http\Traits\ResponseValidationFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\HelpCenter\Models\HelpCenterCategory;
use Modules\HelpCenter\Models\HelpCenterUserGuideImage;
use Modules\HelpCenter\Models\HelpCenterUserGuideVideo;
use Modules\HelpCenter\Traits\ValidationAttributesTrans;

class UpdateUserGuideRequest extends FormRequest
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
//            'category_id' => 'required|exists:help_center_categories,id',
            'category_id' => 'required|exists:'.(new HelpCenterCategory())->getTable().',id',
            'title' => 'required',
            'description' => 'required',
//            'user_type' => ['required',Rule::in(config('HelpCenter.panel.user_types'))],
            'user_types' => 'required|array',
            'user_types.*' => ['required',Rule::in(config('HelpCenter.panel.user_types'))],

            'date' => 'nullable|date',
            'videos' => 'nullable|array',
            'videos.*' => 'file',
            'images' => 'array',
            'images.*' => 'image',

            'deleted_image_ids' => 'array',
//            'deleted_image_ids.*' => 'exists:help_center_user_guide_images,id',
            'deleted_image_ids.*' => 'exists:'.(new HelpCenterUserGuideImage())->getTable().',id',

            'deleted_video_ids' => 'array',
//            'deleted_video_ids.*' => 'exists:help_center_user_guide_videos,id',
            'deleted_video_ids.*' => 'exists:'.(new HelpCenterUserGuideVideo())->getTable().',id',

        ];
    }
}
