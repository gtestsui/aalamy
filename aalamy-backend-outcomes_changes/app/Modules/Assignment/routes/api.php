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

Route::group(['namespace'=>'\Modules\Assignment\Http\Controllers','prefix' => 'api','middleware'=>config('panel.api_global_middleware')],function (){

    Route::get('assignment/{assignment_id}/owner','AssignmentController@getAssignmentOwner');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::group(['middleware' => ['verifiedAccount']], function () {

            Route::group(['middleware' => ['activeAccount']], function () {

                //
                Route::post('separate-file-to-pages','AssignmentController@seperatePdfToPages');

                //Assignment
                Route::get('assignment/{assignment_id}/pages','AssignmentController@getPages');
                Route::get('assignment/my/all-with-count-pages','AssignmentController@getMyAssignmentsWithPagesCount');
                Route::get('assignment/my/all','AssignmentController@getMyAssignments');
                Route::get('assignment/my/all/doesnt-linked-to/roster/{roster_id}','AssignmentController@myAssignmentsDoesntLinkedToDefinedRoster');
                Route::post('assignment/create','AssignmentController@store');
                Route::post('assignment/update/{id}','AssignmentController@update');
                Route::post('assignment/{assignment_id}/download','AssignmentController@mergeAssignmentPdfsAndDownload');
//              //Abd API CODEEE
                Route::get('assignment/{id}','AssignmentController@getById');
                Route::get('assignment/{id}/check/can-show','AssignmentController@checkCanShowAssignment');
//              //END Abd API CODEEE
                Route::delete('assignment/delete/{id}','AssignmentController@softDelete');
                Route::delete('assignment/force-delete/{id}','AssignmentController@destroy');


                //Page
                Route::get('assignment/{assignment_id}/page/{page_id}','PageController@show');
                Route::post('assignment/{assignment_id}/update-pages-order','PageController@updatePagesOrder');
                Route::post('page/empty/create','PageController@createPage');
                Route::post('page/create','PageController@createPage');
                Route::post('page/{page_id}/lock','PageController@lockOrUnLock');
                Route::post('page/{page_id}/hide','PageController@hideAndUnHide');
                Route::delete('page/delete/{id}','PageController@softDelete');


                //AssignmentFolder
                Route::get('assignment-folder/my/paginate','AssignmentFolderController@getMyAssignmentFolders');
                Route::get('assignment-folder/my/root','AssignmentFolderController@getMyRootAssignmentFolders');
                Route::get('assignment-folder/{id}/content/paginate','AssignmentFolderController@getFolderContent');
                Route::post('assignment-folder/create','AssignmentFolderController@store');
                Route::post('assignment-folder/update/{id}','AssignmentFolderController@update');
                Route::delete('assignment-folder/delete/{id}','AssignmentFolderController@softDelete');


                //EditorFile
                    Route::post('editor/file/store','EditorFileController@storeFile');

            });

        });

        Route::group(['namespace'=>'\Modules\Assignment\Http\Controllers\\'.config('panel.super_admin_controllers_folder_name'),
                'prefix'=>config('panel.super_admin_api_prefix'),
                'middleware' =>config('panel.api_global_middleware_for_admin')], function () {

            //Assignment
            Route::get('assignment/paginate/{soft_delete?}','Super_AssignmentController@paginate');
            Route::get('assignment/by-id/{id}/{soft_delete}','Super_AssignmentController@getElementByIdEvenItsDeleted');
            Route::get('class/{class_id}/roster/paginate','Super_AssignmentController@byClassId');
            Route::get('educator/{educator_id}/assignments/all/{soft_delete?}','Super_AssignmentController@getEducatorAssignments');
            Route::get('school/{school_id}/assignments/all/{soft_delete?}','Super_AssignmentController@getSchoolAssignments');
            Route::delete('assignment/{assignment_id}/delete-or-restore','Super_AssignmentController@softDeleteOrRestore');
            Route::delete('assignment/{assignment_id}/force-delete','Super_AssignmentController@destroy');


        });


    });


});




