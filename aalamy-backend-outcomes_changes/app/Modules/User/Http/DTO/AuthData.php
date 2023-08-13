<?php


namespace Modules\User\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class AuthData extends ObjectData
{
    public ?int       $id=null;
    public ?string    $email;
    public ?string    $password;
    public ?string    $service_access_token;
    public ?string    $firebase_token;
    public string    $lang;
    public string    $device_type;

    public static function fromRequest(Request $request): self
    {


         return new self([
            'email' => $request->email,
            'password' => $request->password,
            'service_access_token' => $request->service_access_token,
            'firebase_token' => $request->firebase_token,
            'lang' => $request->lang,
            'device_type' => $request->device_type,



        ]);
    }




}
