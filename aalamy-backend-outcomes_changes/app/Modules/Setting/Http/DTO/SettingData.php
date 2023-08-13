<?php


namespace Modules\Setting\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class SettingData extends ObjectData
{
    public ?int      $id=null;
    public string    $logo;

    public static function fromRequest(Request $request): self
    {

        $logoPath = FileManagmentServicesClass::storeFilesInStaticPath($request->logo,'logo','logo','jpg');
//        $logoPath = FileManagmentServicesClass::storeFiles($request->logo,'logo');
        return new self([
            'logo' => $logoPath,
        ]);
    }

}
