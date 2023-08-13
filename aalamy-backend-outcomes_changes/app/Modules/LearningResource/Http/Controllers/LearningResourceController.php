<?php

namespace Modules\LearningResource\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\Actions\LearningResourceActionsByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\LearningResourceByAccountTypeManagementFactory;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\LearningResourceClass;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByAccountType\MyOwnLearningResource\MyOwnLearningResourceByAccountTypeManagementFactory;
use Modules\LearningResource\Http\DTO\LearningResourceData;
use Modules\LearningResource\Http\Requests\LearningResource\DestroyLearningResourceRequest;
use Modules\LearningResource\Http\Requests\LearningResource\StoreLearningResourceRequest;
use Modules\LearningResource\Http\Requests\LearningResource\UpdateLearningResourceRequest;
use Modules\LearningResource\Http\Resources\LearningResourceResource;
use Modules\LearningResource\Models\LearningResource;


class LearningResourceController extends Controller
{


    public function test(Request $request){
        $user = $request->user();
        DB::enableQueryLog();
        $t = new EducatorTopicManagement($user->Educator);
        $topics = $t->getMyRootAllowedTopic();
        return DB::getQueryLog();
        return $topics;

//        set_time_limit(0);
//
//        return (new AssignmentLearningResourcePdf())->create(32);
//
//        $data = [
//            'title' => 'Welcome to OnlineWebTutorBlog.com',
//            'author' => "Sanjay"
//        ];
//
////        return view('LearningResource::my-pdf-file',$data);
//
//        $pdf = PDF::loadView('LearningResource::my-pdf-file', $data);
//
//        Storage::disk(FileSystemServicesClass::getDiskName())
//            ->put($folderName.'/'.$mediaName, $pdf->output());
//
//        $pdf->save('storage/QuestionTypes/myyfile.pdf');
//        return 's';
    }


    public function getLearningResourceByTopicId($topic_id){
        $learningResourceClass = MyOwnLearningResourceByAccountTypeManagementFactory::create();
        $learningResources = LearningResource::where('topic_id',$topic_id)->paginate(10);
//        $learningResourceClass->getMyLearningResourceByTopicIdPaginate($topic_id);
        return ApiResponseClass::successResponse(LearningResourceResource::collection($learningResources));
    }


    public function store(StoreLearningResourceRequest $request){
        DB::beginTransaction();
        $user = $request->user();
        $topic = $request->getTopic();
        $learningResourceData = LearningResourceData::fromRequest($request);
        $learningResourceClass = new LearningResourceClass();

        $learningResource = $learningResourceClass->create($learningResourceData,$topic);

        DB::commit();
        return ApiResponseClass::successResponse(new LearningResourceResource($learningResource));
    }

    public function update(UpdateLearningResourceRequest $request,$id){
        DB::beginTransaction();
        $user = $request->user();
        $learningResource = $request->getLearningResource();

        if($learningResource->share_type == $request->share_type)
            return ApiResponseClass::successResponse(new LearningResourceResource($learningResource));

        $learningResourceClass = new LearningResourceClass();

        $learningResource = $learningResourceClass->update($learningResource,$request->share_type);

        DB::commit();
        return ApiResponseClass::successResponse(new LearningResourceResource($learningResource));
    }

    //there is missing function to re push the share type when admin restore
    // the deleted learning resource
    public function softDelete(DestroyLearningResourceRequest $request,$id){
        $user = $request->user();
        $learningResource = $request->getLearningResource();

        $learningResourceClass = new LearningResourceClass();
        $learningResourceClass->softDelete($learningResource);

        return ApiResponseClass::deletedResponse();
    }

    public function downloadLearningResource($learning_resource_id){
        $learningResource = LearningResource::findOrFail($learning_resource_id);
        $originalExtension = FileManagmentServicesClass::getExtensionFileFromName($learningResource->getRawOriginal('file'));
//        $originalExtension = ServicesClass::getExtensionFileFromName($file->getRawOriginal('file'));
        $newFileName = time().'.'.$originalExtension;

        return response()->download(
//            config('panel.default_disk_path').$file->getRawOriginal('file'), $newFileName
//            FileSystemServicesClass::getDiskBaseRoot().$learningResource->getRawOriginal('file'), $newFileName
            FileManagmentServicesClass::resolveLinkFromDataToDownload($learningResource,'file'), $newFileName
        );
    }



}
