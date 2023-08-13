<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\EducatorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\User;

class EducatorController extends Controller
{


    public function search(Request $request){
        $user = $request->user();
//        DB::connection()->enableQueryLog();
        $educators = Educator::search($request->key,[],[
            'User'
        ])
        ->when(UserServices::isSchool($user),function ($query)use($user){
            $school = $user->School;
            return $query->WithDefinedSchoolRequest($school);
                /*->withDefinedTeacher($user)*/
        })
        ->with('User')
        ->paginate(10);
//        return DB::getQueryLog();


        return ApiResponseClass::successResponse(EducatorResource::collection($educators));
    }


}
