<?php


namespace Modules\Chat\Http\Controllers\Classes\ChatMessageManagement;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Modules\Assignment\Models\Assignment;
use Modules\Chat\Models\Chat;
use Modules\Chat\Models\ChatMessage;
use Modules\User\Models\User;

abstract class BaseChatMessageClassAbstract
{




    /**
     * @return int
     */
    abstract public function getMyUserId();


    /**
     * @param Chat $chat
     * @return mixed|int
     */
    public function getReceiverUserId(Chat $chat){
        return $chat->first_user_id == $this->getMyUserId()
            ?$chat->second_user_id
            :$chat->first_user_id;
    }

    /**
     * @param Chat $chat
     * @return User
     */
    public function getReceiverUser(Chat $chat){

        $receiverUserId = $this->getReceiverUserId($chat);
        $user = User::where('id',$receiverUserId)->firstOrFail();
        $user->load(ucfirst($user->account_type));
        return $user;

    }



    /**
     * @return ChatMessage
     */
    public function sendMessage(Chat $chat,$message){
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'from_user_id' => $this->getMyUserId(),
            'to_user_id' => $this->getReceiverUserId($chat),
            'message' => $message,
        ]);
        $this->updateChatDateWithMessagesCount($chat);
        return $chatMessage;

    }

    public function updateChatDateWithMessagesCount(Chat $chat){
        $toUserId = $this->getReceiverUserId($chat);
        if($toUserId == $chat->first_user_id){
            $chat->unread_message_count_from_first += 1;
            $chat->it_seen_from_first = false;
        }else{
            $chat->unread_message_count_from_second += 1;
            $chat->it_seen_from_second = false;

        }
        $chat->updated_at =  Carbon::now();
        $chat->save();
    }

//    public function updateChatDate(Chat $chat){
//        $chat->updated_at =  Carbon::now();
//        $chat->save();
//    }

    /**
     * @param $chatId
     * @return ChatMessage|Paginator
     */
    public function getMessagesByChatIdPaginate($chatId){
        return ChatMessage::where('chat_id',$chatId)
            ->doesntDeletedByMe($this->getMyUserId())
            ->paginate(10);

    }


    /**
     * @param $messageId
     * @return ChatMessage|null
     */
    public function getMessageById($messageId){

        return ChatMessage::where('id',$messageId)
            ->doesntDeletedByMe($this->getMyUserId())
            ->first();

    }


    public function deleteMessage(ChatMessage $chatMessage){
        $deletedBy = $chatMessage->deleted_by;
        if(!in_array($this->getMyUserId(),$deletedBy)){
            $deletedBy[] = $this->getMyUserId();
            $chatMessage->update([
                'deleted_by' => $deletedBy
            ]);
        }

    }



}
