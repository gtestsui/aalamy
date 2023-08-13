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

Route::group(['namespace'=>'\Modules\HelpCenter\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {


                //Category
                Route::get('help-center/category/get/all','CategoryController@index');
                Route::get('help-center/category/get/paginate','CategoryController@paginate');


                //UserGuide
                Route::get('help-center/user-guide/get/paginate/by/category/{categoryId}','UserGuideController@getByCategoryPaginate');
                Route::get('help-center/user-guide/{id}','UserGuideController@show');

                //HelpCenterSearch
                Route::get('help-center/search','UserGuideController@search');



            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\HelpCenter\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {

            //Category
            Route::get('help-center/category/get/all/{soft_delete?}','Super_CategoryController@index');
            Route::get('help-center/category/get/by-id/{id}/{soft_delete}','Super_CategoryController@getElementByIdEvenItsDeleted');
            Route::get('help-center/category/get/paginate/{soft_delete?}','Super_CategoryController@paginate');
            Route::get('help-center/category/{id}','Super_CategoryController@show');
            Route::post('help-center/category/create','Super_CategoryController@store');
            Route::post('help-center/category/update/{categoryId}','Super_CategoryController@update');
            Route::delete('help-center/category/delete-or-restore/{categoryId}','Super_CategoryController@softDeleteOrRestore');
            Route::delete('help-center/category/force-delete/{categoryId}','Super_CategoryController@destroy');

            //UserGuide
            Route::get('help-center/user-guide/get/paginate/by/category/{categoryId}/{soft_delete?}','Super_UserGuideController@getByCategoryPaginate');
            Route::get('help-center/user-guide/{id}','Super_UserGuideController@show');
            Route::post('help-center/user-guide/create','Super_UserGuideController@store');
            Route::post('help-center/user-guide/update/{userGuideId}','Super_UserGuideController@update');
            Route::delete('help-center/user-guide/delete-or-restore/{userGuideId}','Super_UserGuideController@softDeleteOrRestore');
            Route::delete('help-center/user-guide/force-delete/{userGuideId}','Super_UserGuideController@destroy');


        });


    });


});




