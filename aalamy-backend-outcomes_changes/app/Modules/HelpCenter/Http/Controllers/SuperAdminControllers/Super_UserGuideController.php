<?php

namespace Modules\HelpCenter\Http\Controllers\SuperAdminControllers;


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

class Super_UserGuideController extends Controller
{

    public function getByCategoryPaginate(Request $request,$categoryId,$soft_delete=null){
        $userGuides = HelpCenterUserGuide::where('category_id',$categoryId)
            ->trashed($soft_delete)
            ->search($request->key,[],[
                'Category'
            ])
            ->with(['Category','Images','Videos'])
            ->order($request->order_by_field,$request->order_type)
            ->paginate(config('HelpCenter.panel.user_guide_paginate_number'));
        return ApiResponseClass::successResponse(UserGuideResource::collection($userGuides));

    }


    public function search(Request $request){

        $userGuides = HelpCenterUserGuide::search($request->key,[],[
            'Category'
        ])
        ->with(['Category','Images','Videos'])
        ->paginate(config('HelpCenter.panel.user_guide_paginate_number'));

        return ApiResponseClass::successResponse(UserGuideResource::collection($userGuides));

    }

    public function show($id){
        $userGuide = HelpCenterUserGuide::with(['Category','Images','Videos'])
            ->findOrFail($id);
        return ApiResponseClass::successResponse(new UserGuideResource($userGuide));
    }


    public function store(StoreUserGuideRequest $request){
        $user = $request->user();
        DB::beginTransaction();
        $userGuideData = UserGuideData::fromRequest($request);
        $userGuide = HelpCenterUserGuide::create($userGuideData->allWithoutRelations());

        $userGuideVideoClass = new UserGuideVideoClass();
        $userGuideVideoClass->addMoreThanVideoToUserGuide($userGuide,$userGuideData->videos);

        $userGuideImageClass = new UserGuideImageClass();
        $userGuideImageClass->addMoreThanImageToUserGuide($userGuide,$userGuideData->images);
        DB::commit();

        return ApiResponseClass::successResponse($userGuide);
    }

    public function update(UpdateUserGuideRequest $request,$userGuideId){
        $user = $request->user();
        DB::beginTransaction();
        $userGuideData = UserGuideData::fromRequest($request);
        $userGuide = HelpCenterUserGuide::findOrFail($userGuideId);
        $userGuide->update($userGuideData->initializeForUpdate($userGuideData));

        $userGuideVideoClass = new UserGuideVideoClass();
        $userGuideVideoClass->deleteMoreThanVideoFromUserGuide($userGuideData->deleted_video_ids);
        $userGuideVideoClass->addMoreThanVideoToUserGuide($userGuide,$userGuideData->videos);

        $userGuideImageClass = new UserGuideImageClass();
        $userGuideImageClass->deleteMoreThanImageFromUserGuide($userGuideData->deleted_image_ids);
        $userGuideImageClass->addMoreThanImageToUserGuide($userGuide,$userGuideData->images);

        DB::commit();
        return ApiResponseClass::successResponse($userGuide);
    }

    public function softDeleteOrRestore(Request $request,$userGuideId){
        $user = $request->user();
        $userGuide = HelpCenterUserGuide::withDeletedItems()
        ->findOrFail($userGuideId);
        $userGuide->softDeleteOrRestore();
        return ApiResponseClass::deletedResponse();

    }

    public function destroy(Request $request,$userGuideId){
        $user = $request->user();
        $userGuide = HelpCenterUserGuide::withDeletedItems()
        ->findOrFail($userGuideId);
        HelpCenterServices::deleteHelpCenter($userGuide);
        return ApiResponseClass::deletedResponse();
    }
}
