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

class SurveyUserResource extends JsonResource
{
    use PaginationResources;

    protected static ?User $currentUser=null ;
    /**
     *
     * @var Educator or School,Student,Parent
     *
     */
    protected static $currentUserAccountObj=null ;

    /**
     * @param  mixed  $resource
     * @param User $user
     * @param Educator|School|Student|ParentModel $currentUserAccountObj
     *
     * if the $user and $currentUserAccountObj not null that mean we
     * declared new from PostResource
     * else that mean we use ::CustomCollection and we make the condition
     * because this values will always passing null if the collection used
     * and we want them values still as initializedValues in CustomCollection
     */
    public function __construct($resource,User $user=null,$currentUserAccountObj=null)
    {
        if(!is_null($user) && !is_null($currentUserAccountObj)){

            Self::$currentUser = $user;
            Self::$currentUserAccountObj = $currentUserAccountObj;
        }

        Parent::__construct($resource);
    }



    public function checkDeleteAuthorization(){
        if(is_null(Self::$currentUser) || is_null(Self::$currentUserAccountObj))
            return false;
        //check if this survey belong to me
        if(Self::$currentUser->id==$this->user_id )
            return true;
        /**
         * check if this survey belongs to my corner
         * $this->{Self::$currentUser->account_type.'_id'} this will give us
         * ether school_id or educator_id depends on account_type
         *
         */
        if(!is_null($this->{Self::$currentUser->account_type.'_id'})){
            if($this->{Self::$currentUser->account_type.'_id'} == Self::$currentUserAccountObj->id)
                return true;
        }
        return false;
    }

    public function checkUpdateAuthorization(){
        if(is_null(Self::$currentUser) || is_null(Self::$currentUserAccountObj))
            return false;
        //check if this survey belong to me
        if(Self::$currentUser->id==$this->user_id )
            return true;

        return false;
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
            'user_id' => isset($this->user_id)?(int)$this->user_id:$this->user_id,
            'survey_id' => isset($this->survey_id)?(int)$this->survey_id:$this->survey_id,
            'User' => new UserResource($this->whenLoaded('User')),
//            'school' => new SchoolResource($this->whenLoaded('School')),
            'survey_questions' => SurveyQuestionResource::collection($this->whenLoaded('SurveyQuestions')),
        ];
    }

    public static function CustomCollection($resource, User $user=null,$currentUserAccountObj=null)
    {
        //you can add as many params as you want.
        self::$currentUser = $user;
        self::$currentUserAccountObj = $currentUserAccountObj;
        return Self::collection($resource);
    }
}
