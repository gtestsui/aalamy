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

Route::group(['namespace'=>'\Modules\SubscriptionPlan\Http\Controllers'],function (){

//    Route::post('subscription-plan/{subscriptionPlanId}/subscribe','SubscribeController@subscribe');
    Route::group(['middleware'=>'getTokenByQueryParameter'],function (){
        Route::get('payment-status/{status}','SubscribeController@getPaymentStatus')
            ->name('payment.status');
    });

    Route::get('payment-operation/success','SubscribeController@showSuccessPaymentOperation')->name('success.payment');
    Route::get('payment-operation/failed','SubscribeController@showFailedPaymentOperation')->name('failed.payment');


});




