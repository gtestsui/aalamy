<?php

use App\Http\Controllers\Classes\ApiResponseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\Exportable;


// use Maatwebsite\Excel\Concerns\FromCollection;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::group(['middleware' => ['guest:api']], function () {
// //     Route::get('t',function (){
// //     	$t =
// //      \Excel::download('file', function($excel) {
// //             require_once("/apppath//vendor/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php");
// //             require_once("/apppath/vendor/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php");

// //             $excel->sheet('New sheet', function($sheet) {

// //                 $sheet->SetCellValue("A1", "UK");
// //                 $sheet->SetCellValue("A2", "USA");

// //                 $sheet->_parent->addNamedRange(
// //                         new \PHPExcel_NamedRange(
// //                         'countries', $sheet, 'A1:A2'
// //                         )
// //                 );


// //                 $sheet->SetCellValue("B1", "London");
// //                 $sheet->SetCellValue("B2", "Birmingham");
// //                 $sheet->SetCellValue("B3", "Leeds");
// //                 $sheet->_parent->addNamedRange(
// //                         new \PHPExcel_NamedRange(
// //                         'UK', $sheet, 'B1:B3'
// //                         )
// //                 );

// //                 $sheet->SetCellValue("C1", "Atlanta");
// //                 $sheet->SetCellValue("C2", "New York");
// //                 $sheet->SetCellValue("C3", "Los Angeles");
// //                 $sheet->_parent->addNamedRange(
// //                         new \PHPExcel_NamedRange(
// //                         'USA', $sheet, 'C1:C3'
// //                         )
// //                 );
// //                 $objValidation = $sheet->getCell('D1')->getDataValidation();
// //                 $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
// //                 $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
// //                 $objValidation->setAllowBlank(false);
// //                 $objValidation->setShowInputMessage(true);
// //                 $objValidation->setShowErrorMessage(true);
// //                 $objValidation->setShowDropDown(true);
// //                 $objValidation->setErrorTitle('Input error');
// //                 $objValidation->setError('Value is not in list.');
// //                 $objValidation->setPromptTitle('Pick from list');
// //                 $objValidation->setPrompt('Please pick a value from the drop-down list.');
// //                 $objValidation->setFormula1('countries'); //note this!
// //             });
//         });

//        return ApiResponseClass::errorMsgResponse('this is my error');
    //     return ApiResponseClass::successMsgResponse('this is my success');
    // });
//    Route::post('register','Auth\AuthController@register');
//    Route::post('login', 'Auth\AuthController@login');
//    Route::post('forgetPassword', 'Auth\AuthController@forgetPassword');
//    Route::post('checkForgetPasswordCode', 'Auth\AuthController@checkForgetPasswordCode');

// });


Route::get('sss',function(){

return 's';
});

// Route::get('initialize-student-studying-information',function (){

//     \Illuminate\Support\Facades\DB::beginTransaction();

//      $yearSetting = \Modules\Setting\Models\YearSetting::first();


//      $students = \Modules\User\Models\Student::with('SchoolStudent')->get();

//      foreach ($students as $student){
//          $studentStudyingInformation = \Modules\Outcomes\Models\StudentStudyingInformation::where('student_id',$student->id)->first();
//      if(is_null($studentStudyingInformation)){
//      continue;
//      }
//          $mark = \Modules\Outcomes\Models\Mark::where('student_studying_information_id',$studentStudyingInformation->id)->first();
//          if(!is_null($mark)){
//              continue;
//          }
//          $classStudent = \Modules\ClassModule\Models\ClassStudent::where('student_id',$student->id)
//          ->active()
//          ->whereNotNull('school_id')
//          ->with('ClassModel')
//          ->first();

//      	if(is_null($classStudent)){
//              continue;
//          }
//          $level = \Modules\Level\Models\Level::where('id',$classStudent->ClassModel->level_id)->first();

//          $levelSubjects = \Modules\Level\Models\LevelSubject::where('level_id',$level->id)
//              ->with(['Subject.BaseSubject.BaseLevelSubjects'=>function($query)use($level){
//                  return $query->where('base_level_id',$level->base_level_id)
//                      ->with('Rule');
//              }])
//              ->get();

//          \Modules\Outcomes\Models\YearGradesGeneralInfo::create([
//              'student_studying_information_id' => $studentStudyingInformation->id
//          ]);

//          $yearGradesTemplate = \Modules\Outcomes\Models\YearGradesTemplate::where('base_level_id',$level->base_level_id)
//              ->with('BaseSubject')
//              ->get();


//          foreach ($levelSubjects as $levelSubject){
//              $subjectGradeTemplate = $yearGradesTemplate->where('base_subject_id',$levelSubject->Subject->base_subject_id)
//                  ->first();


//              if(is_null($subjectGradeTemplate)){
//                  $subjectGradeTemplate = $yearGradesTemplate->where('BaseSubject.code',$levelSubject->Subject->code)
//                      ->first();
//              }

//              if(is_null($subjectGradeTemplate)){
//                  continue;
//              }

//              \Modules\Outcomes\Models\Mark::create([
//                  'year_grade_template_id' => $subjectGradeTemplate->id,
//                  'student_studying_information_id' => $studentStudyingInformation->id,
//                  'subject_id' => $levelSubject->Subject->id,
//                  'level_subject_id' => $levelSubject->id,
//                  'its_one_field' => $levelSubject
//                      ->Subject
//                      ->BaseSubject
//                      ->BaseLevelSubjects[0]
//                      ->Rule
//                      ->its_one_field,
//              ]);
//          }

//      }
//      \Illuminate\Support\Facades\DB::commit();
//      return 'siiiiii';

// });

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('logout', 'Auth\AuthController@logout');
    Route::post('changePassword', 'Auth\AuthController@changePassword');


    Route::group(['middleware' => 'admin','namespace' => 'AdminControllers'],function (){



    });

});









Route::fallback(function(){
    return ApiResponseClass::notFoundResponse('Page Not Found. please insert right Url');
});
