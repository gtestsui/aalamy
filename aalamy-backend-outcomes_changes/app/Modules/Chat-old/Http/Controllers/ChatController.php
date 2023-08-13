<?php

namespace Modules\Chat\Http\Controllers;


use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\SchoolResource;
use Illuminate\Http\Request;
use Modules\Chat\Http\Controllers\Classes\ChatManagement\ChatManagementFactory;
use Modules\Chat\Http\Requests\Chat\DeleteChatRequest;
use Modules\Chat\Http\Requests\Chat\GetMyChatsRequest;
use Modules\Chat\Http\Requests\Chat\MarkChatMessagesAsReadRequest;
use Modules\Chat\Http\Resources\ChatResource;
use Modules\Chat\Models\Chat;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentParentClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;

class ChatController extends Controller
{

    public function getMyChatsPaginate(GetMyChatsRequest $request){
        $user = $request->user();
        $chatManagement = ChatManagementFactory::create($user);
        $chats = $chatManagement->getMyChatsPaginateWithLastMessage();
        if ($user->account_type == 'parent'){
            $firstUserIds = $chats->pluck('first_user_id')->toArray();
            $secondUserIds = $chats->pluck('second_user_id')->toArray();
            list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
            $studentParentClass = new StudentParentClass($accountObject);
            $myStudentIds = $studentParentClass->myStudentIds();
            $schoolIds = SchoolStudent::whereIn('student_id',$myStudentIds)
                ->active()
                ->pluck('school_id')
                ->toArray();
            $schools = School::whereIn('id',$schoolIds)
                ->whereNotIn('user_id',$firstUserIds)
                ->whereNotIn('user_id',$secondUserIds)
                ->get();
        }
        return ApiResponseClass::successResponse([
            'chats' => ChatResource::collection($chats),
            'school' => $user->account_type == 'parent'?SchoolResource::collection($schools):[]
        ]);
    }

	public function getChatsCountHaveUnreadMessages(Request $request){
        $user = $request->user();
        $count1 = Chat::where('first_user_id',$user->id)->where('it_seen_from_first',false)->count();
        $count2 = Chat::where('second_user_id',$user->id)->where('it_seen_from_second',false)->count();
        return ApiResponseClass::successResponse([
            'chats_count' => $count1+$count2
        ]);
    }

	public function markMyChatsAsSeen(Request $request){
        $user = $request->user();
        $chatManagemntClass = ChatManagementFactory::create($user);
        $chats = $chatManagemntClass->getMyChats();
        foreach ($chats as $chat){
            if($user->id == $chat->first_user_id){
                $arrayForUpdate = ['it_seen_from_first' => true];
            }else{
                $arrayForUpdate = ['it_seen_from_second' => true];
            }
            $chat->update($arrayForUpdate);
        }

        return ApiResponseClass::successMsgResponse();
    }

	public function markChatMessagesAsRead(MarkChatMessagesAsReadRequest $request,$chat_id){
        $user = $request->user();
        $chat = $request->getChat();
        if($user->id == $chat->first_user_id){
            $arrayForUpdate = ['unread_message_count_from_first' => 0];
        }else{
            $arrayForUpdate = ['unread_message_count_from_second' => 0];
        }
        $chat->update($arrayForUpdate);
        return ApiResponseClass::successMsgResponse();
    }

//    public function startChat(StartChatRequest $request){
//        $user = $request->user();
//        $chatManagement = ChatManagementFactory::create($user);
//        if($request->school_id){
//            $chat = $chatManagement->startChat($request->school_id,'school');
//        }elseif($request->parent_id){
//            $chat = $chatManagement->startChat($request->parent_id,'parent');
//        }
//        $chat->load(['School.User','Parent.User']);
//        return ApiResponseClass::successResponse(new ChatResource($chat));
//    }

    /**
     * we have deleted_by column when user delete the chat we store his id in this json
     * @param DeleteChatRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ErrorMsgException
     */
    public function destroy(DeleteChatRequest $request,$id){
        $user = $request->user();
        $chat = $request->getChat();

        $chatManagement = ChatManagementFactory::create($user);
        $chatManagement->deleteChat($chat);
        return ApiResponseClass::deletedResponse();

    }



}
