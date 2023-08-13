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

class PostResource extends JsonResource
{
    use PaginationResources;

    protected static ?User $currentUser ;
    /**
     *
     * @var Educator or School,Student,Parent
     */
    protected static $currentUserAccountObj ;

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
        if(!isset(Self::$currentUser) || !isset(Self::$currentUserAccountObj) || is_null(Self::$currentUser) || is_null(Self::$currentUserAccountObj))
            return false;
        //check if this post belong to me
        if(Self::$currentUser->id==$this->user_id )
            return true;
        //check if this post belongs to my corner
        if(!is_null($this->{Self::$currentUser->account_type.'_id'})){
            if($this->{Self::$currentUser->account_type.'_id'} == Self::$currentUserAccountObj->id)
                return true;
        }
        return false;
    }

    public function checkUpdateAuthorization(){
        if(!isset(Self::$currentUser) || !isset(Self::$currentUserAccountObj) || is_null(Self::$currentUser) || is_null(Self::$currentUserAccountObj))
            return false;
        //check if this post belong to me
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
            'user_type' => $this->user_type,
            'school_id' => isset($this->school_id)?(int)$this->school_id:$this->school_id,
            'educator_id' => isset($this->educator_id)?(int)$this->educator_id:$this->educator_id,
            'delete_authorization' => $this->checkDeleteAuthorization(),
            'update_authorization' => $this->checkUpdateAuthorization(),
            'text' => $this->text,
            'priority' => (int)$this->priority/*config('DiscussionCorner.panel.post_priority_names')[$this->priority]*/,
            'is_approved' => (bool)$this->is_approved,
            'replies_count' => (int)$this->replies_count/*$this->when(!is_null($this->replies_count),$this->replies_count)*/,
            'created_at' => refactorCreatedAtFormat($this->created_at),
            'deleted' => (bool)$this->deleted,
            'deleted_at' => $this->deleted_at,
            'replies' => ReplyResource::collection($this->whenLoaded('Replies')),
            'user' => new UserResource($this->whenLoaded('User')),
            'educator' => new EducatorResource($this->whenLoaded('Educator')),
            'school' => new SchoolResource($this->whenLoaded('School')),
            'pictures' => PostPictureResource::collection($this->whenLoaded('Pictures')),
            'videos' => PostVideoResource::collection($this->whenLoaded('Videos')),
            'files' => PostFileResource::collection($this->whenLoaded('Files')),
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
