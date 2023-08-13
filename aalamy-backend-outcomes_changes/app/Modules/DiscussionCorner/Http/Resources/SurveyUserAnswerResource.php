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

class SurveyUserAnswerResource extends JsonResource
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
            'survey_user_id' => isset($this->survey_user_id)?(int)$this->survey_user_id:$this->survey_user_id,
            'question_id' => isset($this->question_id)?(int)$this->question_id:$this->question_id,
            'choice_id' => isset($this->choice_id)?(int)$this->choice_id:$this->choice_id,
            'written_answer' => $this->written_answer,
            'survey_user' => new SurveyUserResource($this->whenLoaded('SurveyUser')),
            'question' => new SurveyQuestionResource($this->whenLoaded('Question')),
            'choice' => new SurveyQuestionChoiceResource($this->whenLoaded('Choice')),

        ];
    }

}
