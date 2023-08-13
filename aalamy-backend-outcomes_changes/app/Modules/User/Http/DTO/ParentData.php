<?php


namespace App\Modules\User\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class ParentData extends ObjectData
{
    public ?int      $id=null;
//    public bool      $is_active;
//    public ?int      $user_id;
//    public ?UserData $user;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {
        return new self([

        ]);
    }

}
