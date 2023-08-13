<?php


namespace Modules\Roster\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Roster\Http\Controllers\Classes\RosterServices;
use Modules\User\Http\Controllers\Classes\UserServices;

final class RosterData extends ObjectData
{
    public ?int      $id=null;
    public ?int      $class_info_id;
    public ?int      $created_by_teacher_id;
    public ?int      $created_by_school_id;
    public ?int      $created_by_educator_id;
    public string    $name;
    public ?bool     $is_closed;
    public string    $color;
    public string    $description;
    public ?string    $code;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,$forUpdate=false): self
    {
        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);

        return new self([
            'class_info_id' => isset($request->class_info_id)?(int)$request->class_info_id:null,
            'name' => $request->name,
            'color' => $request->color,
            'is_closed' => isset($request->is_closed)?(bool)$request->is_closed:false,
            'description' => $request->description,
            'created_by_'.$accountType.'_id' => $forUpdate?null:$accountObject->id,
            //the code won't change while updating
            'code' => $forUpdate?null:RosterServices::generateRosterCode(),
        ]);
    }

}
