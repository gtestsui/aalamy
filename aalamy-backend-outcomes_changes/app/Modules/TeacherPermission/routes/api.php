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

Route::group(['namespace'=>"\Modules\TeacherPermission\Http\Controllers",'prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //PermissionTeacher
                Route::get('teacher-permissions/teacher/{teacher_id}','PermissionTeacherController@getTeacherPermissionsByTeacherId');
                Route::get('teacher-permissions/my','PermissionTeacherController@getMyAllowedPermissions');
                Route::post('teacher-permissions/teacher/{teacher_id}/add-or-delete','PermissionTeacherController@addOrDeletePermissionsToTeacher');


                //Permission
                Route::get('permission/all','PermissionController@getAllPermissions');
                Route::get('permission/with-defined-teacher-permissions/{teacher_id}','PermissionController@getAllPermissionsWithDefinedTeacherPermissions');

            });

        });

        Route::group(['namespace'=>'\Modules\TeacherPermission\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Quiz

        });


    });


});




