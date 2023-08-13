<?php


namespace Modules\Assignment\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Assignment\Models\Assignment;
use Modules\User\Http\Controllers\Classes\UserServices;

final class AssignmentFolderData extends ObjectData
{
    public ?int       $id=null;
    public string     $name;
    public ?int       $parent_id;
    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;


////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,?Assignment $assignment=null): self
    {
        $user = $request->user();
        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );


        return new self([
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,

            'parent_id' => isset($request->assignment_folder_id)?(int)$request->assignment_folder_id:null,

            'name' => isset($request->name)?$request->name:null,



        ]);
    }



}
