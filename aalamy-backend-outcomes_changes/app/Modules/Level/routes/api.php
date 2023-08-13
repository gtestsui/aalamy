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

Route::group(['namespace'=>'\Modules\Level\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //Level
                Route::get('level/get/my-levels','LevelController@myLevels');
                Route::post('level/create','LevelController@store');
                Route::post('level/update/{id}','LevelController@update');
                Route::delete('level/delete/{id}','LevelController@softDelete');
                Route::delete('level/force-delete/{id}','LevelController@destroy');


                //Subject
                Route::get('subject/get/my-subjects/grouped-by-owner','SubjectController@mySubjectsGroupedByOwner');
                Route::get('subject/get/my-subjects','SubjectController@mySubjects');
                Route::get('subject/get/by-semester','SubjectController@mySubjectsBySemester');
                Route::get('subject/student/{student_id}/get','SubjectController@getStudentSubjectsForParent');
                Route::get('subject/get/my-subjects/except-belongs-to-level','SubjectController@mySubjectsExceptBelongsToLevel');
                Route::post('subject/create','SubjectController@store');
                Route::post('subject/update/{id}','SubjectController@update');
                Route::delete('subject/delete/{id}','SubjectController@softDelete');
                Route::delete('subject/force-delete/{id}','SubjectController@destroy');
                Route::get('subject/{subject_id}/rules/by-class/{class_id}','SubjectController@getSubjectRules');


                //LevelSubject
                Route::get('level-subject/get/by/level-id/{levelId}','LevelSubjectController@getByLevelId');
                Route::get('level-subject/get/my-level-subjects/paginate','LevelSubjectController@myLevelSubjectsPaginate');
                Route::get('level-subject/get/my-level-subjects/all','LevelSubjectController@myLevelSubjectsAll');
                Route::post('level/relate-to/many/subject','LevelSubjectController@relateToMoreThanSubject');
                Route::delete('level/delete/subject-relation/{id}','LevelSubjectController@softDelete');
                Route::delete('level/force-delete/subject-relation/{id}','LevelSubjectController@destroy');

                //Unit
                Route::get('unit/get/by/level-subject-id/{level_subject_id}','UnitController@getAllMyUnitsByLevelSubjectId');
                Route::get('unit/get/my-units/paginate','UnitController@getMyUnitsPaginate');
                Route::get('unit/get/my-units/all','UnitController@getMyUnitsAll');
                Route::post('unit/create','UnitController@store');
                Route::post('unit/update/{id}','UnitController@update');
                Route::delete('unit/delete/{id}','UnitController@softDelete');
                Route::delete('unit/force-delete/{id}','UnitController@destroy');

                //Lesson
                Route::get('lesson/get/by/unit-id/{unit_id}','LessonController@getAllMyLessonsByUnitId');
                Route::get('lesson/get/by/unit-ids','LessonController@getAllMyLessonsByUnitIds');
                Route::get('lesson/get/my/all','LessonController@getAllMyLesson');
                Route::get('lesson/get/my-lessons/paginate','LessonController@getMyLessonsPaginate');
                Route::post('lesson/create','LessonController@store');
                Route::post('lesson/update/{id}','LessonController@update');
                Route::delete('lesson/delete/{id}','LessonController@softDelete');
                Route::delete('lesson/force-delete/{id}','LessonController@destroy');




            });

        });

        Route::group(['middleware' =>config('panel.api_global_middleware_for_admin'),
            'namespace'=>'\Modules\Level\Http\Controllers\SuperAdminControllers',
            'prefix'=>config('panel.super_admin_api_prefix')], function () {

            //Level
            Route::get('level/paginate/{soft_delete?}','Super_LevelController@paginate');
            Route::get('level/by-id/{id}/{soft_delete}','Super_LevelController@getElementByIdEvenItsDeleted');
            Route::get('educator/{educator_id}/levels/all/{soft_delete?}','Super_LevelController@getEducatorLevels');
            Route::get('school/{school_id}/levels/all/{soft_delete?}','Super_LevelController@getSchoolLevels');
            Route::get('level/{user_id}/get','Super_LevelController@getLevels');
            Route::delete('level/delete-or-restore/{level_id}','Super_LevelController@softDeleteOrRestore');
            Route::delete('level/force-delete/{level_id}','Super_LevelController@destroy');

            //BaseLevel
            Route::get('base-levels','Super_BaseLevelController@index');
            Route::post('base-level','Super_BaseLevelController@store');
            Route::post('base-level/{id}','Super_BaseLevelController@update');

            //Subject
            Route::get('subject/paginate/{soft_delete?}','Super_SubjectController@paginate');
            Route::get('subject/by-id/{id}/{soft_delete}','Super_SubjectController@getElementByIdEvenItsDeleted');
            Route::get('educator/{educator_id}/subjects/get/{soft_delete?}','Super_SubjectController@getEducatorSubjects');
            Route::get('school/{school_id}/subjects/get/{soft_delete?}','Super_SubjectController@getSchoolSubjects');
            Route::delete('subject/delete-or-restore/{subject_id}','Super_SubjectController@softDeleteOrRestore');
            Route::delete('subject/force-delete/{subject_id}','Super_SubjectController@destroy');

            //BaseSubject
            Route::get('base-subjects','Super_BaseSubjectController@index');
            Route::get('base-subjects/doesnt-belong-to-level/{base_level_id}','Super_BaseSubjectController@baseSubjectsDoesntBelongToBaseLevel');
            Route::get('base-subjects/root','Super_BaseSubjectController@root');
            Route::post('base-subject','Super_BaseSubjectController@store');
            Route::post('base-subject/{id}','Super_BaseSubjectController@update');

            //LevelSubject
            Route::get('level-subject/paginate/{soft_delete?}','Super_LevelSubjectController@paginateWithFilter');
            Route::get('level-subject/by-level/{level_id}','Super_LevelSubjectController@getByLevelId');
            Route::delete('level-subject/delete-or-restore/{level_subject_id}','Super_LevelSubjectController@softDeleteOrRestore');
            Route::delete('level-subject/force-delete/{level_subject_id}','Super_LevelSubjectController@destroy');
            Route::post('storeOrUpdateAll','Super_BaseLevelSubjectRuleController@storeOrUpdateAll');

        

            //BaseLevelSubject
            Route::get('base-level-subjects/paginate','Super_BaseLevelSubjectController@paginate');
            Route::get('base-level-subjects/by-base-level/{base_level_id}','Super_BaseLevelSubjectController@getByBaseLevelId');
            Route::post('base-level/{base_level_id}/relate-with/base-subjects','Super_BaseLevelSubjectController@relateBaseLevelWithMultiBaseSubjects');

            //BaseLevelSubjectRule
            Route::get('base-level-subject/{base_level_subject_id}/rules/get','Super_BaseLevelSubjectRuleController@showByBaseLevelSubjectId');
            Route::post('base-level-subject/{base_level_subject_id}/rules','Super_BaseLevelSubjectRuleController@storeOrUpdate');

            //Unit
            Route::get('unit/paginate/{soft_delete?}','Super_UnitController@paginate');
            Route::get('unit/by-id/{id}/{soft_delete}','Super_UnitController@getElementByIdEvenItsDeleted');
            Route::get('unit/by-level-subject-id/{level_subject_id}','Super_UnitController@getByLevelSubjectId');
            Route::delete('unit/delete-or-restore/{unit_id}','Super_UnitController@softDeleteOrRestore');
            Route::delete('unit/force-delete/{unit_id}','Super_UnitController@destroy');

            //Lessons
            Route::get('lesson/paginate/{soft_delete?}','Super_LessonController@paginate');
            Route::get('lesson/by-id/{id}/{soft_delete}','Super_LessonController@getElementByIdEvenItsDeleted');
            Route::get('lesson/by-unit/{unit_id}','Super_LessonController@getByUnitId');
            Route::delete('lesson/delete-or-restore/{lesson_id}','Super_LessonController@softDeleteOrRestore');
            Route::delete('lesson/force-delete/{lesson_id}','Super_LessonController@destroy');

        });


    });


});




