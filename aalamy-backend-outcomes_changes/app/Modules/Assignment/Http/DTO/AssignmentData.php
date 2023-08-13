<?php


namespace Modules\Assignment\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Assignment\Http\Controllers\Classes\AssignmentSettingClass;
use Modules\Assignment\Models\Assignment;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;
use Carbon\Carbon;

final class AssignmentData extends ObjectData
{
    public ?int       $id=null;
    public ?string     $name;
    public ?string     $description;
    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;
    public ?int       $assignment_folder_id;

    public ?int        $level_subject_id;
    public ?int       $unit_id;
    public ?int       $lesson_id;


    public bool      $is_locked;
    public bool      $is_hidden;
    public bool      $prevent_request_help;
    public bool      $display_mark;
    public bool      $is_auto_saved;
    public bool      $prevent_moved_between_pages;
    public bool      $is_shuffling;

    public array     $pages;

////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,?Assignment $assignment=null): self
    {
        $user = $request->user();
        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );

        //for update just the edited part from settings
        $assignmentSetting = new AssignmentSettingClass($request);

        if(!is_null($assignment)){
            $assignmentSetting->prepareAssignmentSetting($assignment,$request);
        }

        return new self(array_merge($assignmentSetting->all(),[
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,
            'assignment_folder_id' => isset($request->assignment_folder_id)?(int)$request->assignment_folder_id:null,

            'name' => isset($request->name)?$request->name:null,
            'description' => isset($request->description)?$request->description:null,

            'level_subject_id' => isset($request->level_subject_id)?(int)$request->level_subject_id:null,
            'unit_id' => isset($request->unit_id)?(int)$request->unit_id:null,
            'lesson_id' => isset($request->lesson_id)?(int)$request->lesson_id:null,

            'pages' => isset($request->pages)?$request->pages:[],

        ]));
    }

    public function allSettings(){
        return [

            'is_locked' => $this->is_locked,

            'is_hidden' => $this->is_hidden,

            'prevent_request_help' => $this->prevent_request_help,

            'display_mark' => $this->display_mark,

            'is_auto_saved' => $this->is_auto_saved,

            'prevent_moved_between_pages' => $this->prevent_moved_between_pages,

            'is_shuffling' => $this->is_shuffling,

        ];
    }


}
