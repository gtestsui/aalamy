<?php

namespace Modules\Feedback\Http\Controllers\Classes\ManageFiles;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use Illuminate\Http\UploadedFile;
use Modules\Feedback\Http\DTO\FeedbackAboutStudentData;
use Modules\Feedback\Models\FeedbackAboutStudent;
use Modules\Feedback\Models\FeedbackAboutStudentFile;

class FeedbackAboutStudentFileClass implements FeedbackAboutStudentFileInterface
{

    public function addToFeedback(FeedbackAboutStudent $feedback,FeedbackAboutStudentData $feedbackAboutStudentData){
        $path = FileManagmentServicesClass::storeFiles($feedbackAboutStudentData->file,"feedback_about_students/{$feedbackAboutStudentData->student_id}/files");
        return FeedbackAboutStudentFile::create([
            'feedback_id' => $feedback->id,
            'file' => $path,
        ]);
    }

    public function deleteFromFeedback($id){
        return FeedbackAboutStudentFile::findOrFail($id)->delete();
    }


}
