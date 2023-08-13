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

Route::group(['namespace'=>'\Modules\ClassModule\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Class
                Route::get('class/get/by/level-id/{levelId}','ClassController@getByLevelId');
                Route::get('class/my-classes','ClassController@myClasses');
                Route::get('class/{id}','ClassController@show');
                Route::get('class/{id}/check-if-im-teacher-inside','ClassController@checkIfImTeacherInsideClass');
                Route::post('class/create','ClassController@store');
                Route::post('class/create/with-class-infos','ClassController@createWithClassInfo');
                Route::post('class/update/{id}','ClassController@update');
                Route::delete('class/delete/{id}','ClassController@destroy');

                //ClassStudent
                Route::get('class/{class_id}/student/get','ClassStudentController@getByClassId');
                Route::post('class/{class_id}/student/add/many','ClassStudentController@addMoreThanStudent');
                Route::post('class/{class_id}/student/{student_id}','ClassStudentController@addStudentToClassOrMove');
                Route::post('class/{class_id}/student/delete/many','ClassStudentController@softDeleteMoreThanStudent');
                Route::post('class/{class_id}/student/force-delete/many','ClassStudentController@destroyMoreThanStudent');

                //ClassInfo
                Route::get('class/{class_id}/info/distinct-on-teacher','ClassInfoController@getClassInfoDistinctOnTeacherId');
                Route::get('class/{class_id}/info','ClassInfoController@getByClassId');
                Route::post('class/{class_id}/info','ClassInfoController@store');
                Route::post('class/{class_id}/info/many-level-subjects','ClassInfoController@storeWithManyLevelSubject');
                Route::post('class/{class_id}/info/update/{id}','ClassInfoController@update');
                Route::delete('class/{class_id}/info/delete/{id}','ClassInfoController@softDelete');
                Route::delete('class/{class_id}/info/force-delete/{id}','ClassInfoController@destroy');



            });

        });

        Route::group(['namespace'=>'\Modules\ClassModule\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
            'prefix'=>config('panel.super_admin_api_prefix'),
            'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Classes
            Route::get('class/paginate/{soft_delete?}','Super_ClassController@paginate');
            Route::get('educator/{educator_id}/classes/all/{soft_delete?}','Super_ClassController@getEducatorClasses');
            Route::get('school/{school_id}/classes/all/{soft_delete?}','Super_ClassController@getSchoolClasses');
            Route::get('class/by-id/{id}/{soft_delete}','Super_ClassController@getElementByIdEvenItsDeleted');
            Route::delete('class/{class_id}/delete-or-restore','Super_ClassController@softDeleteOrRestore');
            Route::delete('class/{class_id}/force-delete','Super_ClassController@destroy');

        });


    });


});




