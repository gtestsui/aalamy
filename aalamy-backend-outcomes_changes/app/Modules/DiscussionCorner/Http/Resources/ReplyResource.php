<?php

namespace Modules\DiscussionCorner\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;

class ReplyResource extends JsonResource
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
            'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
            'post_id' => isset($this->post_id)?(int)$this->post_id:$this->post_id,
            'text' => $this->text,
            'picture' => $this->picture,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'user' => new UserResource($this->whenLoaded('User')),
            'post' => new PostResource($this->whenLoaded('Post')),

        ];
    }
}
