<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Models\FirebaseToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FirebaseController extends Controller
{

    public function saveFirebaseToken(Request $request){
        $validator = Validator::make($request->all(),[
            'firebase_toke' => 'required',
        ]);
        if($validator->fails())
            return ApiResponseClass::validateResponse($validator->errors());
        $user = $request->user();

        FirebaseToken::create([
           'user_id' => $user->id,
           'token' => $request->firebase_token
        ]);

        return ApiResponseClass::successMsgResponse();
    }

}
