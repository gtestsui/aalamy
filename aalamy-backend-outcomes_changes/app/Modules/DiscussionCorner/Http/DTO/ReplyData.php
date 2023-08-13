<?php


namespace Modules\DiscussionCorner\Http\DTO;


use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

final class ReplyData extends ObjectData
{
    public ?int      $id=null;
    public int       $user_id;
    public ?int      $post_id;
    public ?string      $text;
    public ?string      $picture;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,bool $forUpdate=false): self
    {
        $user = $request->user();
        $picture = null;
        if(isset($request->picture))
            $picture = FileManagmentServicesClass::storeFiles($request->picture,
                "replies-pictures/$request->post_id/".$user->getFullName());
        return new self([
            'user_id'    => (int)$user->id,
            'post_id'    => $forUpdate?null:(int)$request->post_id,
            'text'       => $request->text,
            'picture'    => $picture,

        ]);
    }




}
