<?php

namespace Modules\Assignment\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Assignment\Http\Controllers\Classes\AssignmentPageServices;
use Modules\Assignment\Http\Controllers\Classes\AssignmentServices;
use Modules\Assignment\Http\Controllers\Classes\ManageAssignment\AssignmentManagementFactory;
use Modules\Assignment\Http\DTO\AssignmentData;
use Modules\Assignment\Http\Requests\Assignment\CanShowAssignmentRequest;
use Modules\Assignment\Http\Requests\Assignment\DestroyAssignmentRequest;
use Modules\Assignment\Http\Requests\Assignment\GetMyAssignmentsDoesntLinkedToRosterRequest;
use Modules\Assignment\Http\Requests\Assignment\GetMyAssignmentsRequest;
use Modules\Assignment\Http\Requests\Assignment\GetMyAssignmentsWithPagesCountRequest;
use Modules\Assignment\Http\Requests\Assignment\MergeAssignmentPdfsAndDownloadRequest;
use Modules\Assignment\Http\Requests\Assignment\SeperatePdfToImagesRequest;
use Modules\Assignment\Http\Requests\Assignment\StoreAssignmentRequest;
use Modules\Assignment\Http\Requests\Assignment\UpdateAssignmentRequest;
use Modules\Assignment\Http\Resources\AssignmentResource;
use Modules\Assignment\Http\Resources\PageResource;
use Modules\Assignment\Models\Assignment;
use Modules\Assignment\Models\Page;
use Modules\RosterAssignment\Http\Controllers\Classes\ManageRosterAssignmentPage\StudentRosterAssignmentPage;

use Modules\User\Http\Controllers\Classes\UserServices;
use Spatie\PdfToImage\Pdf;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use Webklex\PDFMerger\Facades\PDFMergerFacade;

class AssignmentController extends Controller
{


    public function getAssignmentOwner($assignment_id){
        DB::enableQueryLog();
        $assignmentInfo = Assignment::withoutGlobalScopes()
            ->where('assignments.id',$assignment_id)
            ->leftJoin('schools','schools.id','=','assignments.school_id')
            ->leftJoin('users as schools_users','schools.user_id','=','schools_users.id')
            ->leftJoin('teachers','teachers.id','=','assignments.teacher_id')
            ->leftJoin('users as teachers_users','teachers.user_id','=','teachers_users.id')
            ->leftJoin('educators','educators.id','=','assignments.educator_id')
            ->leftJoin('users as educators_users','educators.user_id','=','educators_users.id')
            ->select('educators_users.unique_username as educator_unique_username',
                'schools_users.unique_username as school_unique_username',
                'teachers_users.unique_username as teacher_unique_username',
                'assignments.is_locked as is_locked',
            )
            ->firstOrFail();
//            dd(DB::getqueryLog());
        $uniqueUserName = null;
        $isLocked = $assignmentInfo->is_locked;
        if(!is_null($assignmentInfo->educator_unique_username))
            $uniqueUserName = $assignmentInfo->educator_unique_username;
        if(!is_null($assignmentInfo->school_unique_username))
            $uniqueUserName = $assignmentInfo->school_unique_username;
        if(!is_null($assignmentInfo->teacher_unique_username))
            $uniqueUserName = $assignmentInfo->teacher_unique_username;

        return ApiResponseClass::successResponse([
            'user_name' => $uniqueUserName,
            'is_locked' => $isLocked,
        ]);

    }


    public function mergeAssignmentPdfsAndDownload(MergeAssignmentPdfsAndDownloadRequest $request,$assignment_id){


        $pdf = PDFMergerFacade::init();

        foreach ($request->pdf_files as $key => $value) {
            $path = FileManagmentServicesClass::storeBase64File($value,'assignment-pages-merger-for-download');
            $pdf->addPDF(FileSystemServicesClass::getDiskBaseRoot().$path, 'all');
        }

        $fileName = 'assignment'.$assignment_id.'.pdf';
        $pdf->merge();
        $toFolderPath = FileSystemServicesClass::getDiskRoot()."/assignment-for-download";
        if(!File::exists($toFolderPath)) {
            File::makeDirectory($toFolderPath);
        }
        $pdf->save(FileSystemServicesClass::getDiskRoot().
            "/assignment-for-download/".$fileName
        );

        $b64Doc = chunk_split(base64_encode(file_get_contents(FileSystemServicesClass::getDiskRoot().
            "/assignment-for-download/".$fileName)));


        return ApiResponseClass::successResponse([
//            'file' => $b64Doc,
            'path' => baseRoute()."storage/assignment-for-download/".$fileName,

        ]);

    }


    public function seperatePdfToPages(SeperatePdfToImagesRequest $request){
    	ini_set('max_execution_time', '0');
        $user = $request->user();
        $separetedImagesPaths = [];
        $filePath = FileManagmentServicesClass::storeFiles($request->file,'files-for-separate');

        $pdf = new Pdf(FileSystemServicesClass::getDiskBaseRoot().$filePath);

        $currentTimeStamp = Carbon::now()->microsecond;
//        $pathToWhereImageShouldBeStored=FileSystemServicesClass::getDiskRoot().'/images-from-separated-files';
//        File::makeDirectory($pathToWhereImageShouldBeStored);
        $pathToWhereImageShouldBeStored=FileSystemServicesClass::getDiskRoot().'/images-from-separated-files/'.$currentTimeStamp;
        File::makeDirectory($pathToWhereImageShouldBeStored);

        $numberOfPages = $pdf->getNumberOfPages();
        for($i=1;$i<=$numberOfPages;$i++){
            $pdf->setPage($i)
                ->saveImage($pathToWhereImageShouldBeStored);
            Image::make($pathToWhereImageShouldBeStored.'/'.$i.'.jpg')
//                ->resize(1024,768)
                ->save();
            $separetedImagesPaths[] = baseRoute()
                .FileSystemServicesClass::getDefaultStoragePathInsideDisk()
                ."/images-from-separated-files/$currentTimeStamp".'/'.$i.'.jpg';

        }

        return ApiResponseClass::successResponse($separetedImagesPaths);
    }



    public function getMyAssignmentsWithPagesCount(GetMyAssignmentsWithPagesCountRequest $request){
        $user = $request->user();
        $manageAssignmentClass = AssignmentManagementFactory::create($user,$request->my_teacher_id);
        $assignments = $manageAssignmentClass->myAssignmentsWithPagesCount();

        return ApiResponseClass::successResponse(AssignmentResource::collection($assignments));
    }

    public function myAssignmentsDoesntLinkedToDefinedRoster(GetMyAssignmentsDoesntLinkedToRosterRequest $request,$roster_id){
        $user = $request->user();
        $manageAssignmentClass = AssignmentManagementFactory::create($user,$request->my_teacher_id);
        $myAssignments = $manageAssignmentClass->myAssignmentsDoesntLinkedToRoster($roster_id);
        return ApiResponseClass::successResponse(AssignmentResource::collection($myAssignments));
    }

    public function getMyAssignments(GetMyAssignmentsRequest $request){
        $user = $request->user();
        $manageAssignmentClass = AssignmentManagementFactory::create($user,$request->my_teacher_id);
        $assignments = $manageAssignmentClass->myAssignments();

        return ApiResponseClass::successResponse(AssignmentResource::collection($assignments));
    }

    public function checkCanShowAssignment(CanShowAssignmentRequest $request,$assignment_id){
        $user = $request->user();
        $assignmentClass = AssignmentManagementFactory::create($user,$request->my_teacher_id);
//        $assignment = $assignmentClass->myAssignmentById($assignment_id);
        $permission = $assignmentClass->checkCanShowAssignment($assignment_id);
        return ApiResponseClass::successResponse([
            'can_show_assignment' => $permission
        ]);


    }


    public function getById(Request $request,$id){
        $user = $request->user();
        $manageAssignmentClass = AssignmentManagementFactory::create($user,$request->my_teacher_id);
        $assignment = $manageAssignmentClass->myAssignmentByIdWithPages($id);
        return ApiResponseClass::successResponse(new AssignmentResource($assignment));
    }




    public function store(StoreAssignmentRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $assignmentData = AssignmentData::fromRequest($request);
        $assignment = Assignment::create($assignmentData->all());
        AssignmentPageServices::addPagesToAssignment($assignmentData->pages,$assignment);
        DB::commit();
        return ApiResponseClass::successResponse(new AssignmentResource($assignment));
    }

    public function update(UpdateAssignmentRequest $request,$id){
        $user = $request->user();
        DB::beginTransaction();
        $assignment = $request->getAssignment();
        $assignmentData = AssignmentData::fromRequest($request,$assignment);
        $assignment->update($assignmentData->initializeForUpdate($assignmentData));
        AssignmentServices::updateAllRelatedRosterAssignmentsSettings($assignment->id,$assignmentData);
        DB::commit();
        return ApiResponseClass::successResponse(new AssignmentResource($assignment));
    }


    public function softDelete(DestroyAssignmentRequest $request,$id){
        DB::beginTransaction();
        $user = $request->user();
        $assignment = $request->getAssignment();
        $assignment->softDeleteObject();
        DB::commit();
        return ApiResponseClass::deletedResponse();

    }

    public function destroy(DestroyAssignmentRequest $request,$id){
        $user = $request->user();
        $assignment = $request->getAssignment();
        $assignment->delete();
        return ApiResponseClass::deletedResponse();
    }


    public function getPages(Request $request,$assignment_id){
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);
        if($accountType == 'student'){
            $studentRosterAssignmentPage = new StudentRosterAssignmentPage($accountObject);
            $pagesIds = $studentRosterAssignmentPage->getMyRosterAssignmentPagesByRosterAssignmentId($request->roster_assignment_id)
                ->pluck('page_id')->toArray();
            $pages = Page::whereIn('id',$pagesIds)->get();
        }else{
            $pages = Page::where('assignment_id',$assignment_id)->get();
        }
        return ApiResponseClass::successResponse(PageResource::collection($pages));
    }



}
