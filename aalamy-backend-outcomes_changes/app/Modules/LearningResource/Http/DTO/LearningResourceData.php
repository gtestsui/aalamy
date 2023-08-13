<?php


namespace Modules\LearningResource\Http\DTO;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\LearningResource\Http\Controllers\Classes\LearningResourceServices;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByFileType\AssignmentLearningResource;
use Modules\LearningResource\Http\Controllers\Classes\ManageLearningResourceByFileType\GeneratedAssignmentManagementFactory;
use Modules\LearningResource\Models\Topic;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

final class LearningResourceData extends ObjectData
{
    public ?int      $id=null;
    public ?int      $user_id;
    public ?int      $school_id;
    public ?int      $educator_id;
    public ?int      $teacher_id;
    public ?int      $topic_id;
    public ?string   $share_type;
    public int       $level_subject_id;
    public ?int      $unit_id;
    public ?int      $lesson_id;
    public string    $name;
    public ?string    $file;
    public ?string    $file_type;
    public ?int      $assignment_id;
    public ?array    $page_ids;

////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request): self
    {
        $user = $request->user();

        //if the user choose topic then make learningResource owners as same the chosen topic
        list($schoolId,$teacherId,$educatorId) = LearningResourceServices::prepareLearningResourceOwner(
            $user,$request
        );

        //we have check here because the types its dynamic depend on topic
        LearningResourceServices::checkValidShareTypeWithMyAccount($schoolId,$teacherId,$educatorId,$request->share_type);


        $filePath = null;
        if(isset($request->file)){
            if($request->file_type != configFromModule('panel.learning_resource_file_types.link',ApplicationModules::LEARNING_RESOURCE_MODULE_NAME)){
                $filePath = FileManagmentServicesClass::storeFiles($request->file,'learning-resource/'.$request->file_type.'/uploaded_files',$request->name);

            }else{
                $filePath = $request->file;
            }
        }


        return new self([
            'user_id'   => $user->id,
            'school_id'   => isset($schoolId)?(int)$schoolId:null,
            'teacher_id'   => isset($teacherId)?(int)$teacherId:null,
            'educator_id' => isset($educatorId)?(int)$educatorId:null,
            'share_type' => $request->share_type,
            'topic_id'    => isset($request->topic_id)?(int)$request->topic_id:null,
            'level_subject_id'    => isset($request->level_subject_id)?(int)$request->level_subject_id:null,
            'unit_id'    => isset($request->unit_id)?(int)$request->unit_id:null,
            'lesson_id'    => isset($request->lesson_id)?(int)$request->lesson_id:null,
            'name'        => $request->name,
            'file'        => $filePath,
            'file_type'        => $request->file_type,
            'assignment_id'        => isset($request->assignment_id)?(int)$request->assignment_id:null,
            'page_ids'        => $request->page_ids,

        ]);
    }




}
