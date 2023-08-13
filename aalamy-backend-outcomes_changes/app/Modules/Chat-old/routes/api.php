<?php
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//

Route::group(['namespace'=>'\Modules\Chat\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {
                //Chat
                Route::post('chat/{chat_id}/unread-messages/mark-as-read','ChatController@markChatMessagesAsRead');
                Route::post('chat/my/mark-as-seen','ChatController@markMyChatsAsSeen');
                Route::get('chat/have-unread-messages/count','ChatController@getChatsCountHaveUnreadMessages');
                Route::get('chat/my/paginate','ChatController@getMyChatsPaginate');
                Route::delete('chat/{id}','ChatController@destroy');
//                Route::post('chat/start','ChatController@startChat');

                //ChatMessage
                Route::get('chat/{chat_id}/messages','ChatMessageController@getMessagesByChatId');
                Route::post('chat/{chat_id?}','ChatMessageController@sendMessage');
                Route::delete('chat/message/{id}','ChatMessageController@destroy');

            });

        });

        Route::group(['namespace'=>'\Modules\Chat\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {
            //Chat

        });


    });


});




