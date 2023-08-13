<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFiles;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Illuminate\Http\UploadedFile;
use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Models\FeedbackAboutStudentImage;

class FeedbackAboutStudentImageClass implements FeedbackAboutStudentFileInterface
{

    public function addToFeedback(FeedbackAboutStudent $feedback,FeedbackAboutStudentData $feedbackAboutStudentData/* ?UploadedFile $image,$studentId=null*/){
        $path = FileManagmentServicesClass::storeFiles($feedbackAboutStudentData->image,"feedback_about_students/{$feedbackAboutStudentData->student_id}/images");
//        $path = ServicesClass::storeFiles($image,"feedback_about_students/{$studentId}/images");
        return FeedbackAboutStudentImage::create([
            'feedback_id' => $feedback->id,
            'image' => $path,
        ]);
    }

    public function deleteFromFeedback($imageId){
        return FeedbackAboutStudentImage::findOrFail($imageId)->delete();
    }


}
