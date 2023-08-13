<?php

namespace Modules\StudentAchievement\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class StudentAchievementResource extends JsonResource
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
            'student_id' => isset($this->student_id)?(int)$this->student_id:$this->student_id,
            'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'is_published' => (bool)$this->is_published,
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'file_type' => $this->file_type,
            'picture' => config('StudentAchievement.panel.achievements_file_types.picture')==$this->file_type
                ?$this->file
                :null,
            'file' => config('StudentAchievement.panel.achievements_file_types.file')==$this->file_type
                ?$this->file
                :null,
//            config(
//                'StudentAchievement.panel.achievements_file_types.'.$this->file_type
//            ) => $this->file,
            'student' => new StudentResource($this->whenLoaded('Student')),
            'user' => new UserResource($this->whenLoaded('User'))
        ];
    }
}
