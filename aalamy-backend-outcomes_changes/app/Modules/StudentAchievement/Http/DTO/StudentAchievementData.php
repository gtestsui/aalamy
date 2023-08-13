<?php


namespace Modules\StudentAchievement\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class StudentAchievementData extends ObjectData
{
    public ?int      $id=null;
    public ?int    $student_id;
    public int    $user_id;
    public string    $title;
    public string    $description;
    public ?string    $file;
    public ?string    $file_type;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,$user): self
    {
        $filePath=null;


        if(isset($request->file))
            $filePath = FileManagmentServicesClass::storeFiles($request->file,"student_achievement/{$request->student_id}",$request->title);
//            $filePath = ServicesClass::storeFiles($request->file,"student_achievement/{$request->student_id}",$request->title);
        return new self([
            'student_id' => (int)$request->student_id,
            'title' => $request->title,
            'description' => $request->description,
            'file' => $filePath,
            'file_type' => $request->file_type,
            'user_id' => $user->id,

        ]);
    }

}
