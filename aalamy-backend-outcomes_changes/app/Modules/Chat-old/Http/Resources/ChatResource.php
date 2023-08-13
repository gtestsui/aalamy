<?php

namespace Modules\Chat\Http\Resources;

use App\Http\Traits\PaginationResources;
use App\Modules\User\Http\Resources\ParentResource;
use App\Modules\User\Http\Resources\SchoolResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\UserResource;

class ChatResource extends JsonResource
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
        //always put me as first user
        $user = $request->user();
        $firstUserId = $user->id;
        $firstUserRelation = 'FirstUser';
        $secondUserId = $user->id;
        $secondUserRelation = 'FirstUser';
        if($user->id == $this->first_user_id){
            //im the first user
            $firstUserId = $this->first_user_id;
            $secondUserId = $this->second_user_id;
            $firstUserRelation = 'FirstUser';
            $secondUserRelation = 'SecondUser';
            $unreadMessageCount = 'unread_message_count_from_first';

        }else{
            //im the second user
            $firstUserId = $this->second_user_id;
            $secondUserId = $this->first_user_id;
            $firstUserRelation = 'SecondUser';
            $secondUserRelation = 'FirstUser';
            $unreadMessageCount = 'unread_message_count_from_second';

        }
        return [
            'id' => $this->id,
            'first_user_id' => (int)$firstUserId,
            'second_user_id' => (int)$secondUserId,
            'created_at' => refactorCreatedAtFormat($this->created_at) ,
            'first_user' => new UserResource($this->whenLoaded($firstUserRelation)),
            'second_user' => new UserResource($this->whenLoaded($secondUserRelation)),
            'last_message' => new ChatMessageResource($this->whenLoaded('LastMessage')),
            'unread_messages_count' => $this->{$unreadMessageCount},

        ];
    }
}
