<?php


namespace Modules\Level\Http\DTO;


use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Models\Teacher;

final class UnitData extends ObjectData
{
    public ?int      $id=null;
    public string    $name;
    public ?int    $user_id;
    public int    $level_subject_id;
    public ?string    $type;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,$user,$forUpdate=0): self
    {
        $accountType = $user->account_type;
        if(isset($request->my_teacher_id)){
            $accountType = 'teacher';
        }

        return new self([
            'user_id' => !$forUpdate?(int)$user->id:null,
            'name' => $request->name,
            'level_subject_id' => (int)$request->level_subject_id,
            'type' => !$forUpdate?$accountType:null,

        ]);
    }

}
