<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ServicesClass;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Notification\Models\FirebaseToken;
use Modules\User\Models\LoggedDevice;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class FirebaseServices
{

    public static function saveFirebaseToken(User $user,$firebaseToken,$lang=null):bool
    {
        $siteLangs = config('panel.site_languages');
        if(is_null($lang) ||!in_array($lang,$siteLangs))
            $lang = config('panel.site_languages.en');

        $foundToken = FirebaseToken::where('token',$firebaseToken)
            ->where('user_id',$user->id)
            ->first();

        if(is_null($foundToken))
            FirebaseToken::create([
                'user_id' => $user->id,
                'token' => $firebaseToken,
                'lang' => $lang
            ]);
        else
            $foundToken->update([
                'updated_at' => Carbon::now(),
                'lang' => $lang,
            ]);

        return true;
    }


    public static function deleteFireBaseToken(User $user,$firebaseToken):bool
    {
        FirebaseToken::where('user_id',$user->id)
            ->where('token',$firebaseToken)->delete();

        return true;
    }

    public static function updateLang(User $user,$firebaseToken,$lang){
        $foundToken = FirebaseToken::where('token',$firebaseToken)
            ->where('user_id',$user->id)
            ->update([
                'lang' => $lang,
            ]);

    }


}
