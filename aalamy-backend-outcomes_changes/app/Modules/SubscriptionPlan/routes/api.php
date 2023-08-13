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

Route::group(['namespace'=>'\Modules\SubscriptionPlan\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\SubscriptionPlan\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix')],function (){

            //SubscriptionPlan
            Route::get('subscription-plan/all','Super_SubscriptionPlanController@index');
            Route::get('subscription-plan/by-id/{id}','Super_SubscriptionPlanController@show');
            Route::post('subscription-plan/create','Super_SubscriptionPlanController@store');
            Route::post('subscription-plan/update/{id}','Super_SubscriptionPlanController@update');
            Route::post('subscription-plan/{id}/active-or-un-active','Super_SubscriptionPlanController@activateOrUnActivate');
            Route::delete('subscription-plan/delete/{id}','Super_SubscriptionPlanController@destroy');


            //Modules
            Route::get('module/all','Super_ModuleController@index');
            Route::get('module/{type}/all','Super_ModuleController@getModuleByOwnerType');
            Route::post('module/create','Super_ModuleController@store');
            Route::post('module/update/{id}','Super_ModuleController@update');
            Route::delete('module/delete/{id}','Super_ModuleController@destroy');

            //UserSubscription
            Route::get('user-subscription/paginate','Super_SubscribeController@paginate');
            Route::get('user-subscription/by/subscription-plan-id/{subscription_plan_id}/paginate','Super_SubscribeController@getBySubscriptionPlanId');
			Route::get('subscription-plan/assignment-editor/check-can-use','SubscribeController@checkIfCanUseAssignmentEditor');

        

        });

//        Route::post('subscription-plan/{subscriptionPlanId}/subscribe','SubscribeController@subscribe');

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //SubscriptionPlan
                Route::get('subscription-plan/plans-by-account-type','SubscriptionPlanController@getPlansByAccountType');

                //Subscribe
                Route::post('subscription-plan/{subscriptionPlanId}/subscribe','SubscribeController@subscribe');
                Route::get('subscription-plan/my/module-usage','SubscribeController@getMyModuleUsageDetails');
                Route::get('subscription-plan/assignment-editor/check-can-use','SubscribeController@checkIfCanUseAssignmentEditor');


            });

        });


    });

    //SubscriptionPlan
    Route::get('subscription-plan/all','SubscriptionPlanController@index');
    Route::get('subscription-plan/plans-by-type/{type}','SubscriptionPlanController@getplansByType');

    Route::group(['middleware' => ['guest:api']], function () {

    });


});




