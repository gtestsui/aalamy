<?php

namespace Modules\User\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\EducatorResource;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\SchoolResource;
use App\Modules\User\Http\Resources\StudentResource;
use App\Modules\User\Http\Resources\TeacherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Address\Http\Resources\AddressResource;
use Modules\Level\Http\Resources\LevelResource;
use Modules\Level\Http\Resources\SubjectResource;
use Modules\User\Traits\CatchUserType;

class UserResource extends JsonResource
{
    use PaginationResources,CatchUserType;





    /**
     * this function check if the my_token exists that mean im the one who
     * make the request
     * else there is another one maybe try display my details!
     */
    public function checkFoundToken(){
        return isset($this->{config('User.panel.auth_token_name')})?true:false;
    }
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
            'fname'=> $this->fname,
            'lname'=> $this->lname,
            'full_name' => $this->fname.' '.$this->lname,
            'email'=> $this->email,
            'verified_status'=> (int)$this->verified_status,
            'image'=> $this->image,
            'address_id'=> isset($this->address_id)?(int)$this->address_id:$this->address_id,
            'account_type'=> $this->account_type,
            'phone_code'=> $this->phone_code,
            'phone_iso_code'=> $this->phone_iso_code,
            'phone_number'=> $this->phone_number,
            'phone'=> $this->phone_code.' '.$this->phone_number,
            'gender'=> $this->gender,
            'date_of_birth'=> $this->date_of_birth,
            'unique_username'=> $this->unique_username,
            'created_at'=> refactorCreatedAtFormat($this->created_at),
            'deleted'=> (bool)$this->deleted,
            'deleted_at'=> $this->deleted_at,
            'my_token' => $this->when($this->checkFoundToken(),$this->{config('User.panel.auth_token_name')}),
//            'account_id'=> $this->account_id,
//            'login_service_id'=> $this->login_service_id,
            'student' => (new StudentResource($this->whenLoaded('Student'),Self::$userType)),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'parent' => new ParentResource($this->whenLoaded('Parent')),
            'school' => new SchoolResource($this->whenLoaded('School')),
            'address' => new AddressResource($this->whenLoaded('Address')),
            'teachers_account' => TeacherResource::collection($this->whenLoaded('Teachers')),
            'subjects' => SubjectResource::collection($this->whenLoaded('Subjects')),
            'levels' => LevelResource::collection($this->whenLoaded('Levels')),

        ];
    }

}
