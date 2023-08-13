<?php


namespace Modules\SubscriptionPlan\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;

final class ModuleData extends ObjectData
{
    public ?int      $id=null;
    public string    $name;
    public string    $description;
//    public ?int      $number;
//    public ?bool      $deleted;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {
        return new self([
            'name' => $request->name,
            'description' => $request->description,
//            'number' => (int)$request->number,

        ]);
    }
}
