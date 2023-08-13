<?php


namespace Modules\Chat\Http\Controllers\Classes\ChatManagement;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Chat\Models\Chat;

abstract class BaseChatClassAbstract
{


    /**
     * @return int
     */
    abstract public function getMyUserId();


    /**
     * @return Builder
     */
    public function getMyChatsQuery(){
        return Chat::query()->where(function ($query){
                return $query->where('first_user_id',$this->getMyUserId())
                    ->orWhere('second_user_id',$this->getMyUserId());
            })
            ->doesntDeletedByMe($this->getMyUserId());

    }

    /**
     * we need to load FirstUser and SecondUser because maybe my user_id is found in first_user_id or in second_user_id
     * we have loaded just school because other type have all needed information in user object
     * @return Builder
     */
    public function getMyChatsWithSenderQuery(){
        return $this->getMyChatsQuery()->with(['FirstUser.School','SecondUser.School']);
    }



    /**
     * @return Chat
     */
    public function startChat($withUserId){
        //check if there is a perv chat even it deleted(deleted_by)
        $chat = Chat::whereIn('first_user_id',[$withUserId,$this->getMyUserId()])
            ->whereIn('second_user_id',[$withUserId,$this->getMyUserId()])
            ->first();

        if(is_null($chat)){
            $chat = Chat::create([
                'first_user_id'=> $this->getMyUserId(),
                'second_user_id' => $withUserId
            ]);
        }
        $chat->load('SecondUser');
        $chat->SecondUser->load(ucfirst($chat->SecondUser->account_type));
        return $chat;

    }

    /**
     * @return Chat|Collection
     */
    public function getMyChats(){
        return $this->getMyChatsQuery()->get();
    }


    /**
     * @return Chat|LengthAwarePaginator
     */
    public function getMyChatsPaginateWithLastMessage(){
        return $this->getMyChatsWithSenderQuery()
            ->with('LastMessage')
            ->paginate(10);
    }


    /**
     * @return Chat|null|object
     */
    public function getMyChatById($id){
        return $this->getMyChatsQuery()->where('id',$id)->first();
    }


    /**
     * @throws ModelNotFoundException
     * @return Chat|object
     */
    public function getMyChatByIdOrFail($id){
        return $this->getMyChatsQuery()->findOrFail($id);
    }



    public function deleteChat(Chat $chat){
        $deletedBy = $chat->deleted_by;
        if(!in_array($this->getMyUserId(),$deletedBy)){
            $deletedBy[] = $this->getMyUserId();
            $chat->update([
                'deleted_by' => $deletedBy
            ]);
        }

    }


}
