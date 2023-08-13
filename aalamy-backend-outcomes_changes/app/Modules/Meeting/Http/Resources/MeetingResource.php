<?php

namespace Modules\Meeting\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Meeting\Traits\ResourceSharedData\MeetingResourceSharedDataTrait;

class MeetingResource extends JsonResource
{
    use PaginationResources;


    use MeetingResourceSharedDataTrait;



    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return array_merge($this->getSharedData(),[
            'target_users' =>  MeetingTargetUserResource::collection($this->whenLoaded('TargetUsers')),

        ]);

    }
}
