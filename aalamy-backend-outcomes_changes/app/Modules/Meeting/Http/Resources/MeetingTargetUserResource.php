<?php

namespace Modules\Meeting\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\StudentResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Meeting\Traits\ResourceSharedData\MeetingTargetUserResourceSharedDataTrait;

class MeetingTargetUserResource extends JsonResource
{
    use PaginationResources;

    use MeetingTargetUserResourceSharedDataTrait;




    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return array_merge($this->getSharedData(),[
            'meeting' => new MeetingResource($this->whenLoaded('Meeting')),
        ]);


    }
}
