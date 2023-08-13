<?php


namespace Modules\Setting\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Roster\Http\Controllers\Classes\RosterServices;

final class DeleteDataSettingData extends ObjectData
{
    public ?int      $id=null;
    public int       $time_for_force_delete_data;
    public string    $type;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'time_for_force_delete_data' => (int)$request->time_for_force_delete_data,
            'type' => $request->type,
        ]);
    }

}
