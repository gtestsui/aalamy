<?php


namespace Modules\ContactUs\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;
use Carbon\Carbon;

final class ContactUsData extends ObjectData
{
    public ?int       $id=null;
    public ?int       $user_id;
    public ?string    $subject;
    public ?string    $text;

////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {
        $user = $request->user();
        return new self([
            'user_id' => $user->id ,
            'subject' => $request->subject,
            'text' => $request->text,
        ]);
    }

}
