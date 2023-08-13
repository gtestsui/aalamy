<?php

namespace App\Events;

use App\Exceptions\ErrorMsgException;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Chat\Models\Chat;
use Modules\Chat\Models\ChatMessage;
use Modules\User\Models\User;

class SendMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


   
    private ChatMessage $chatMessage;
    private Chat $chat;
    private User $receiverUser;
    public  $message;
    public  $sender_user_id;
    public  $sender_user_account_type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat $chat,ChatMessage $chatMessage,User $receiverUser,User $sender)
    {
        $this->chatMessage = $chatMessage;
        $this->message = $chatMessage;
        $this->chat = $chat;
        $this->receiverUser = $receiverUser;
        $this->sender_user_id = $sender->id;
        $this->sender_user_account_type = $sender->account_type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {

        return [
        
            new Channel('Chat.'.$this->chat->id.'.To.'.$this->chatMessage->to_user_id),
            new Channel('App.Models.User.'.$this->receiverUser->id),
        
            // new PrivateChannel('Chat.'.$this->chat->id.'.To.'.$this->chatMessage->to),
            // new PrivateChannel('App.Models.User.'.$this->receiverUser->id),
        ];
    }


    public function broadcastAs(){
        return 'new_message';
    }

}
