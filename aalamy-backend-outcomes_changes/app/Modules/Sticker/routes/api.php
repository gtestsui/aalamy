<?php

use App\Http\Controllers\Classes\ApplicationModules;
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

Route::group(['namespace'=>"\Modules\Sticker\Http\Controllers",'prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Sticker
                Route::get('sticker/my/all','StickerController@getAllMyStickers');
                Route::post('sticker/create','StickerController@store');
                Route::delete('sticker/force-delete/{sticker_id}','StickerController@destroy');

                //StudentPageSticker
                Route::get('roster-assignment/{roster_assignment_id}/students-pages-stickers','StudentPageStickerController@getStudentsPagesStickers');
                Route::get('roster-assignment/{roster_assignment_id}/page/{page_id}/student-user/{student_user_id}/stickers','StudentPageStickerController@getStudentPageStickersByStudentAndPage');
                Route::post('roster-assignment/{roster_assignment_id}/page/{page_id}/student-user/{student_user_id}/sticker/{sticker_id}/add','StudentPageStickerController@addStickerOnStudentPage');
                Route::delete('roster-assignment/{roster_assignment_id}/page/{page_id}/student-user/{student_user_id}/sticker/{sticker_id}/delete','StudentPageStickerController@deleteStickerFromStudentPage');



            });

        });

        Route::group(['namespace'=>'\Modules\Sticker\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

        });


    });


});




