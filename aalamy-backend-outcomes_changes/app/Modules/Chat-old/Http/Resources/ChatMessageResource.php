<?php

namespace Modules\Chat\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class ChatMessageResource extends JsonResource
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
            'chat_id' => (int)$this->chat_id,
            'from_user_id' => (int)$this->from_user_id,
            'to_user_id' => (int)$this->to_user_id,
            'message' => $this->message,
            'created_at' => refactorCreatedAtFormat($this->created_at) ,
        ];
    }
}
