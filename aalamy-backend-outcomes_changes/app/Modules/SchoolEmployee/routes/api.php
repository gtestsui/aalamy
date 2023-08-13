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

Route::group(['namespace'=>'\Modules\SchoolEmployee\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {
                //SchoolEmployee
                Route::get('school/employees/my','SchoolEmployeeController@getMyEmployees');
                Route::get('school/employees/my/{id}','SchoolEmployeeController@show');
                Route::post('school/employee','SchoolEmployeeController@store');
                Route::post('school/employee/found-teacher','SchoolEmployeeController@storeFoundTeacher');
                Route::post('school/employee/{id}','SchoolEmployeeController@update');
                Route::delete('school/employee/{id}','SchoolEmployeeController@destroy');

                //Teacher
                Route::get('school/teacher/doesnt-belongs-to-employee','SchoolEmployeeController@getTeacherDoesntBelongsToEmployee');
                Route::get('school/teacher/{teacher_id}/info','SchoolEmployeeController@getMyTeacherInfo');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\SchoolEmployee\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {



        });


    });


});




