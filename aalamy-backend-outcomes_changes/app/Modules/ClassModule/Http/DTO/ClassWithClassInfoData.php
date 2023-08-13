<?php


namespace Modules\ClassModule\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Level\Http\Controllers\Classes\ManageLevel\LevelManagementFactory;
use Modules\User\Http\Controllers\Classes\UserServices;

final class ClassWithClassInfoData extends ObjectData
{
    public ?int      $id=null;
    public ?int     $teacher_id;
    public ?int     $educator_id;
    public ?int     $school_id;
    public int      $level_id;
    public string   $name;
    public array    $level_subject_ids;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        $user = $request->user();

        $levelSubjectIds = [];
        if(isset($request->level_subject_ids)){
            $levelClass = LevelManagementFactory::create($user);
            $levelSubjectIds = $levelClass->myLevelSubjectsQuery()
                ->whereIn('id',$request->level_subject_ids)
                ->pluck('id')
                ->toArray();
        }

        $isSchool = UserServices::isSchool($user);
        $isTeacher = UserServices::isTeacher($user);
        return new self([
            'teacher_id' => $isTeacher?(int)$request->my_teacher_id:null,
            'educator_id' => $isSchool||$isTeacher?null:$user->Educator->id,
            'school_id' => $isSchool?$user->School->id:null,
            'level_id' => (int)$request->level_id,
            'name' => $request->name,
            'level_subject_ids' => $levelSubjectIds,

        ]);

    }

}
