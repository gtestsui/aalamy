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

class SurveyQuestionChoiceResource extends JsonResource
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
            'question_id' => isset($this->question_id)?(int)$this->question_id:$this->question_id,
            'choice' => $this->choice,
            'counter' => (float)$this->counter,
            'question' => new SurveyQuestionResource($this->whenLoaded('Question')),
            'survey_user_answers' => SurveyUserAnswerResource::collection($this->whenLoaded('SurveyUserAnswers')),
            'limited_survey_user_answers' => SurveyUserAnswerResource::collection($this->whenLoaded('LimitedSurveyUserAnswers')),

        ];
    }

}
