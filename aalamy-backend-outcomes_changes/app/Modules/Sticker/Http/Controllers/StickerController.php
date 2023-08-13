<?php

namespace Modules\Sticker\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Sticker\Http\Controllers\Classes\ManageSticker\StickerManagementFactory;
use Modules\Sticker\Http\DTO\StickerData;
use Modules\Sticker\Http\Requests\Sticker\DestroyStickerRequest;
use Modules\Sticker\Http\Requests\Sticker\GetMyStickersRequest;
use Modules\Sticker\Http\Requests\Sticker\StoreStickerRequest;
use Modules\Sticker\Http\Resources\StickerResource;
use Modules\Sticker\Models\Sticker;

class StickerController extends Controller
{



    public function getAllMyStickers(GetMyStickersRequest $request){
        $user = $request->user();
        $stickerClass = StickerManagementFactory::create($user);
        $myStickers = $stickerClass->getAllMyStickers();
        return ApiResponseClass::successResponse(StickerResource::collection($myStickers));
    }

    public function store(StoreStickerRequest $request){
        $user = $request->user();
        $stickerData = StickerData::fromRequest($request);
        $sticker = Sticker::create($stickerData->all());
        return ApiResponseClass::successResponse(new StickerResource($sticker));
    }


    public function destroy(DestroyStickerRequest $request,$sticker_id){
        $user = $request->user();
        $stickerClass = StickerManagementFactory::create($user);
        $sticker = $stickerClass->getMyStickerByIdOrFail($sticker_id);
        $sticker->delete();
        return ApiResponseClass::deletedResponse();
    }






}
