<?php
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;

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

Route::group(['namespace'=>'\Modules\User\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {
                //Profile
                Route::post('user/lang/update','UserController@updateClientLang');
                Route::get('user/account','UserController@myInfo');
                Route::get('user/account/details','UserController@getDetails');
                Route::get('user/{user_id}/profile','UserController@getProfile');
                Route::post('user/account/personal-info-with-account/update','UserController@updateAccountWithPersonalInfo');
                Route::post('user/account/personal-info/update','UserController@updatePersonalInfo');
                Route::post('user/account/info/update','UserController@updateAccountInfo');
                Route::post('user/account/password/update','UserController@updatePassword');

                //ParentStudent
                Route::get('parent/student/all','ParentStudentController@myStudents');
                Route::get('parent/have-students-belongs-to-me/paginate','ParentStudentController@getParentHaveStudentsBelongToMe');
                Route::get('parent/have-students-belongs-to-class/{class_id}/all','ParentStudentController@getParentHaveStudentBelongToClass');
                Route::post('parent-student/add/by/code','ParentStudentController@store');
                Route::post('parent/student/{student_id}/send-link/from/{accountType}','ParentStudentController@sendParentLink');
                Route::delete('parent/student/delete/{parent_student_id}','ParentStudentController@destroy');

                //Student
                Route::match(['get','post'],'student/search','StudentController@search');
                Route::get('student/my-active-school','StudentController@getMyActiveSchool');
                //this 3 apis have the same job
                Route::get('student/belongs-to-me/paginate','StudentController@getStudentsBelongsToMePaginate');
                Route::get('student/belongs-to-me/with-parents-info/paginate','StudentController@getStudentsBelongsToMeWithParentsInfo');
                Route::get('school/students/all','StudentController@getStudentsBelongsToMePaginate');
                Route::get('educator/my-students/all','EducatorStudentController@myStudents');

                Route::get('student/my-active-educators','StudentController@getMyActiveEducators');

                //SchoolStudent
//                Route::get('school/students/all','SchoolStudentController@myStudents');
                Route::get('school/students/doesnt-have-class/paginate','SchoolStudentController@getAllMyStudentsDoesntBelongsToClassPaginate');
                Route::get('school/students/doesnt-have-class/all','SchoolStudentController@getAllMyStudentsDoesntBelongsToClass');
                Route::post('school/students/create','SchoolStudentController@createStudent');
                Route::post('school/students/update/{school_student_id}','SchoolStudentController@updateStudent');
                Route::post('school/students/update-information/{school_student_id}','SchoolStudentController@updateStudentWithInformation');
                Route::get('school/students/{school_student_id}/all-information','SchoolStudentController@getStudentAllInformation');
                Route::post('school/students/class/{class_id}/import/{file_type}','SchoolStudentController@import');
                Route::delete('school/student/delete/{school_student_id}','SchoolStudentController@destroy');

                //School
                Route::get('school/my-teachers','SchoolController@getMyTeachers');
                Route::get('school/my-teachers/all','SchoolController@getAllMyTeachers');
                Route::get('school/my-teachers/by-class/{class_id}','SchoolController@getAllMyTeachersByClassId');
                Route::delete('school/teachers/delete/{teacher_id}','SchoolController@deleteTeacherFromMySchool');
                Route::post('school/update','SchoolController@update');
                Route::post('school/search','SchoolController@search');

                //Teacher
                Route::get('teacher/my-accounts','TeacherController@myTeacherAccounts');
                Route::post('teacher/{my_teacher_id}','TeacherController@update');

                //Educator
                Route::post('educator/search','EducatorController@search');


                //EducatorStudent
//                Route::get('educator/my-students/all','EducatorStudentController@myStudents');
                Route::get('educator/students/doesnt-have-class/all','EducatorStudentController@getAllMyStudentsDoesntBelongsToClass');
                Route::get('educator/students/doesnt-belongs-to-roster/{roster_id}','EducatorStudentController@getAllMyStudentsDoesntBelongsToDefinedRoster');
                Route::post('educator/students/create','EducatorStudentController@createStudent');
                Route::post('educator/students/roster/{roster_id}/import/{file_type}','EducatorStudentController@import');
                Route::delete('educator/student/delete/{educator_student_id}','EducatorStudentController@destroy');

            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\User\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {
            Route::post('logout','Super_AuthController@logout');

            //User
            Route::get('user/get/limited','Super_UserController@limitedUsersGroupedByType');
            Route::get('user/get/by-account-type/{account_type}/{soft_delete?}','Super_UserController@getUserPaginateByType');
            Route::get('user/by-id/{id}/{soft_delete}','Super_UserController@getElementByIdEvenItsDeleted');
            Route::get('user/{userId}/account','Super_UserController@getUserAccount');
//            Route::get('user/{userId}/details','Super_UserController@showUserDetails');
            Route::delete('user/delete-or-restore/{userId}','Super_UserController@softDeleteOrRestore');
            Route::delete('user/force-delete/{userId}','Super_UserController@destroy');
            Route::post('user/{userId}/activate-or-deactivate','Super_UserController@activateOrDeActivate');
            Route::post('user/{userId}/update','Super_UserController@update');
//            Route::post('user/{userId}/deactivate','Super_UserController@deactivate');

            //SchoolInformation
            //SchoolStudents
            Route::get('school/{school_id}/students/get','Super_SchoolController@getStudents');
            //SchoolStudents
            Route::get('school/{school_id}/students-parents/all','Super_SchoolController@getStudentParents');
            //SchoolTeachers
            Route::get('school/{school_id}/teachers/get','Super_SchoolController@getTeachers');
            //Details
            Route::get('school/{school_id}/details','Super_SchoolController@getDetails');



            //EducatorInformation
            //EducatorStudents
            Route::get('educator/{educator_id}/students/get','Super_EducatorController@getStudents');
            //EducatorStudentsParents
            Route::get('educator/{educator_id}/students-parents/all','Super_EducatorController@getStudentParents');
            //EducatorTeachers
            Route::get('educator/{educator_id}/teachers/get','Super_EducatorController@getTeachers');
            //Details
            Route::get('educator/{educator_id}/details','Super_EducatorController@getDetails');


            //ParentInformation
            Route::get('parent/{parent_id}/details','Super_ParentController@getDetails');

            //StudentInformation
            Route::get('student/{student_id}/details','Super_StudentController@getDetails');
            Route::get('class/{class_id}/students','Super_StudentController@getByClassId');


        });
        Route::post('account-confirmation','UserController@confirmAccount');
        Route::post('account-confirmation/resend','UserController@resendConfirmationAccountCode');
        Route::get('account-confirmation/allowed-time-to-resend-date','UserController@getAllowedResendDate');
        Route::post('logout','UserController@logout');

        //ChangePassword
        Route::post('change-password','ForgetPasswordController@changePassword');



    });

    Route::group(['middleware' => ['guest:api']], function () {

        Route::post('sign-up/{accountType}','UserController@register');
        Route::post('login','UserController@login');

        //service login
        Route::post('sign-up/{accountType}/{service}','AuthByServiceController@register');
        Route::post('login/{service}','AuthByServiceController@login');


        //admin login
        Route::post('super-admin/login','SuperAdminControllers\Super_AuthController@login');

        //ForgetPassword
        Route::post('forget-password/send-code','ForgetPasswordController@forgetPassword');
        Route::post('forget-password/check-code','ForgetPasswordController@checkForgetPasswordCode');

    });

});




