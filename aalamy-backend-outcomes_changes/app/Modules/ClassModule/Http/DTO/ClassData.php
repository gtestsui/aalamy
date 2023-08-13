<?php


namespace Modules\ClassModule\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class ClassData extends ObjectData
{
    public ?int      $id=null;
    public int    $level_id;
    public string    $name;
    public array    $level_subject_ids;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'level_id' => (int)$request->level_id,
            'name' => $request->name,
            'level_subject_ids' => isset($request->level_subject_ids)?$request->level_subject_ids:[],
        ]);
    }

}
