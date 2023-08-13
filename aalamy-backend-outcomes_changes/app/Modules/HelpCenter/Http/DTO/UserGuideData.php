<?php


namespace Modules\HelpCenter\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class UserGuideData extends ObjectData
{
    public ?int       $id=null;
    public int        $category_id;
    public string     $title;
    public string     $description;
//    public string     $user_type;
    public array      $user_types;
    public ?Carbon    $date;
    public ?array     $videos;
    public ?array     $images;
    public ?array     $deleted_image_ids;
    public ?array     $deleted_video_ids;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'title' => $request->title,
            'category_id' => (int)$request->category_id,
            'description' => $request->description,
            'user_types' => $request->user_types,
            'date' => isset($request->date)?Parent::generateCarbonObject($request->date):null,
            'videos' => $request->videos,
            'images' => $request->images,
            'deleted_image_ids' => $request->deleted_image_ids,
            'deleted_video_ids' => $request->deleted_video_ids,
        ]);
    }

    public function allWithoutRelations(): array
    {

        return [
            'title' => $this->title,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'user_types' => $this->user_types,
            'date' => $this->date,

        ];
    }

}
