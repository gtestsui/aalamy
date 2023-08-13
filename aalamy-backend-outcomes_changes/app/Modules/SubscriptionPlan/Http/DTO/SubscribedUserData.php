<?php


namespace Modules\SubscriptionPlan\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\SubscriptionPlan\Http\Controllers\Classes\SubscriptionPlanServices;
use Modules\User\Models\User;

final class SubscribedUserData extends ObjectData
{

    public User      $user;
    public string    $access_token;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
            'user' => $request->user(),
            'access_token' => getUserTokenFromRequest($request),


        ]);
    }

}
