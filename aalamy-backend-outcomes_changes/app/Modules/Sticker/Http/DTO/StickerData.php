<?php


namespace Modules\Sticker\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class StickerData extends ObjectData
{
    public ?int       $id=null;
    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;
    public string     $name;
    public string     $icon;
    public int        $mark;

    public static function fromRequest(Request $request): self
    {
        $user = $request->user();
        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );
        $iconPath = FileManagmentServicesClass::storeFiles($request->icon,"stickers/".$user->getFullName());

        return new self([
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,
            'name' => $request->name,
            'icon' => $iconPath,
            'mark' => (int)$request->mark,

        ]);
    }


}
