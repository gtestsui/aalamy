<?php


namespace Modules\ClassModule\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

final class ClassInfoData extends ObjectData
{
    public ?int     $id=null;
    public ?int     $teacher_id;
    public ?int     $educator_id;
    public ?int     $school_id;
    public int      $level_subject_id;
    public int      $class_id;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,User $user): self
    {
        $isSchool = UserServices::isSchool($user);
        return new self([
            'teacher_id' => $isSchool?(int)$request->teacher_id:null,
            'educator_id' => $isSchool?null:$user->Educator->id,
            'school_id' => $isSchool?$user->School->id:null,
            'level_subject_id' => (int)$request->level_subject_id,
            'class_id' => (int)$request->class_id,


        ]);
    }

}
