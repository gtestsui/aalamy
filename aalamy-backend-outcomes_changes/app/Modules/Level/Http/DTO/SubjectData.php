<?php


namespace Modules\Level\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class SubjectData extends ObjectData
{
    public ?int      $id=null;
    public string    $name;
    public int    $user_id;
    public string    $type;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,$user): self
    {

        return new self([
            'user_id' => $user->id,
            'name' => $request->name,
            'type' => $user->account_type,

        ]);
    }

}
