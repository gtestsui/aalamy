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

Route::group(['namespace'=>'\Modules\Notification\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {
                //Notifications
                Route::get('notification/count','NotificationController@getMyNotificationsCount');
                Route::get('notification/paginate','NotificationController@getMyNotificationsPaginate');
                Route::get('notification/paginate/with-unseen-count','NotificationController@getMyNotifications');
                Route::post('notification/{id}/mark/as-read','NotificationController@markAsRead');
                Route::post('notification/mark/as-seen','NotificationController@markAsSeen');

                //ManualNotifications
                Route::get('notification/manual/my-sent-notification','ManualNotificationController@getMySentManualNotification');
                Route::post('notification/manual/send','ManualNotificationController@send');

            });

        });


    });


});




