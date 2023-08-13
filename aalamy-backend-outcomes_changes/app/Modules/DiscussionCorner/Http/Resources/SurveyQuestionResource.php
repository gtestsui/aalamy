<?php

namespace Modules\DiscussionCorner\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class SurveyQuestionResource extends JsonResource
{
    use PaginationResources;


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'survey_id' => isset($this->survey_id)?(int)$this->survey_id:$this->survey_id,
            'question' => $this->question,
            'question_type' => $this->question_type,
            'is_required' => (bool)$this->is_required,
            'answered_user_count' => $this->when(isset($this->answered_user_count),(int)$this->answered_user_count),
            'survey' => new SurveyResource($this->whenLoaded('Survey')),
            'choices' => SurveyQuestionChoiceResource::collection($this->whenLoaded('Choices')),
            'survey_user_answers' => SurveyUserAnswerResource::collection($this->whenLoaded('SurveyUserAnswers')),
            'limited_survey_user_answers' => SurveyUserAnswerResource::collection($this->whenLoaded('LimitedSurveyUserAnswers')),

        ];
    }

}
