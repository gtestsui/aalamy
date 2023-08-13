<?php

namespace Modules\Quiz\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\UnitResource;
use Modules\Quiz\Traits\CatchIsQuizOwner;
use Modules\Quiz\Traits\ResourceSharedData\QuizResourceSharedDataTrait;

class QuizUnitResource extends JsonResource
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
            'unit' => new UnitResource($this->whenLoaded('Unit')),

        ];

    }
}
