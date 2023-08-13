<?php

namespace Modules\HelpCenter\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\HelpCenter\Http\Requests\UserGuide\StoreUserGuideRequest;
use Modules\HelpCenter\Http\Requests\UserGuide\UpdateUserGuideRequest;
use App\Modules\HelpCenter\Http\Resources\UserGuideResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HelpCenter\Http\Controllers\Classes\UserGuideImageClass;
use Modules\HelpCenter\Http\Controllers\Classes\UserGuideVideoClass;
use Modules\HelpCenter\Models\HelpCenterCategory;
use Modules\HelpCenter\Models\HelpCenterUserGuide;
use Modules\HelpCenter\Http\DTO\UserGuideData;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class UserGuideController extends Controller
{

    public function getByCategoryPaginate(Request $request,$categoryId){
        $user = $request->user();
        $userGuides = HelpCenterUserGuide::search($request->key,[],[
                'Category'
            ])
            ->where('category_id',$categoryId)
            ->whereJsonContains('user_types',$user->account_type)
            ->with(['Category','Images','Videos'])
            ->order($request->order_by_field,$request->order_type)
            ->paginate(config('HelpCenter.panel.user_guide_paginate_number'));
        return ApiResponseClass::successResponse(UserGuideResource::collection($userGuides));

    }

    public function search(Request $request){
        $user = $request->user();
        $userGuides = HelpCenterUserGuide::search($request->key,[],[
            'Category'
        ])
        ->whereJsonContains('user_types',$user->account_type)
        ->with(['Category','Images','Videos'])
        ->paginate(config('HelpCenter.panel.user_guide_paginate_number'));


        return ApiResponseClass::successResponse(UserGuideResource::collection($userGuides));

    }

    public function show(Request $request,$id){
        $user = $request->user();
        $userGuide = HelpCenterUserGuide::whereJsonContains('user_types',$user->account_type)
            ->with(['Category','Images','Videos'])
            ->findOrFail($id);
        return ApiResponseClass::successResponse(new UserGuideResource($userGuide));
    }

}
