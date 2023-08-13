<?php

namespace Modules\Chat\Http\Controllers;

use App\Events\SendMessage;
use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Chat\Http\Controllers\Classes\ChatManagement\ChatManagementFactory;
use Modules\Chat\Http\Controllers\Classes\ChatMessageManagement\ChatMessageManagementFactory;
use Modules\Chat\Http\Requests\ChatMessage\DestroyChatMessageRequest;
use Modules\Chat\Http\Requests\ChatMessage\GetChatMessagesByChatIdRequest;
use Modules\Chat\Http\Requests\ChatMessage\SendChatMessageRequest;
use Modules\Chat\Http\Resources\ChatMessageResource;
use Modules\Chat\Http\Resources\ChatResource;

class ChatMessageController extends Controller
{


    public function getMessagesByChatId(GetChatMessagesByChatIdRequest $request,$chat_id){
        $user = $request->user();
        $chatMessageManagement = ChatMessageManagementFactory::create($user);
        $chatMessages = $chatMessageManagement->getMessagesByChatIdPaginate($chat_id);
        return ApiResponseClass::successResponse(ChatMessageResource::collection($chatMessages));
    }

    public function sendMessage(SendChatMessageRequest $request,$chat_id=null){
        $user = $request->user();
        DB::beginTransaction();
        if(is_null($chat_id)){//this is first chat between these two users
            $chatManagement = ChatManagementFactory::create($user);
            $chat = $chatManagement->startChat($request->to_user_id);

        }else{
            $chat = $request->getChat();
        }

        if(count($chat->deleted_by) != 0){ //check if the chat deleted from one at least of the (sender and receiver)
            $chat->update([
                'deleted_by' => [],
            ]);
        }

        $chatMessageManagement = ChatMessageManagementFactory::create($user);
        $chatMessage = $chatMessageManagement->sendMessage($chat,$request->message);
        $receiverUser = $chatMessageManagement->getReceiverUser($chat);
        broadcast(new SendMessage($chat,$chatMessage,$receiverUser,$user));
        DB::commit();
        return ApiResponseClass::successResponse(new ChatMessageResource($chatMessage));
    }

    public function destroy(DestroyChatMessageRequest $request ,$id){
        $user = $request->user();
        $chatMessage = $request->getChatMessage();
        $chatMessageManagement = ChatMessageManagementFactory::create($user);
        $chatMessageManagement->deleteMessage($chatMessage);
        return ApiResponseClass::deletedResponse();
    }



}
