<?php


namespace Modules\HelpCenter\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class CategoryData extends ObjectData
{
    public ?int      $id=null;
    public string    $name;
    public string    $description;
    public ?string    $image;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {
        $imagePath=null;


        if(isset($request->image))
            $imagePath = FileManagmentServicesClass::storeFiles($request->image,"help_center/category/{$request->name}/images",$request->name);
//            $imagePath = ServicesClass::storeFiles($request->image,"help_center/category/{$request->name}/images",$request->name);
        return new self([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,

        ]);
    }

}
