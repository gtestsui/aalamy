<?php

namespace Modules\SchoolEmployee\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Level\Http\Resources\SubjectResource;

class SchoolEmployeeCertificateResource extends JsonResource
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
            'school_employee_id' => (int)$this->school_employee_id,

            'certificate' => $this->certificate,
            'file_type' => $this->file_type,


        ];
    }
}
