<?php


namespace Modules\Address\Http\DTO;

use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class CityData extends ObjectData
{
    public ?int      $id=null;
    public string     $name_en;
    public string     $name_ar;
    public int        $state_id;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'state_id' => (int)$request->state_id,

        ]);
    }

}
