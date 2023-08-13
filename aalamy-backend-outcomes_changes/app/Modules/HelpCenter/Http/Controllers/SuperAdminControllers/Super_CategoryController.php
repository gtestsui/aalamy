<?php

namespace Modules\HelpCenter\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HelpCenter\Http\Requests\Category\StoreCategoryRequest;
use Modules\HelpCenter\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Modules\HelpCenter\Http\Resources\CategoryResource;
use Modules\HelpCenter\Models\HelpCenterCategory;
use Modules\HelpCenter\Http\DTO\CategoryData;

class Super_CategoryController extends Controller
{

    public function index(Request  $request,$soft_delete=null){
        $categories = HelpCenterCategory::unitedAdminUserCategory($request->order_by_field,$request->order_type)
            ->search($request->key)
            ->trashed($soft_delete)
            ->get();
        return ApiResponseClass::successResponse(CategoryResource::collection($categories));
    }

    public function paginate(Request $request,$soft_delete=null){
        $categories = HelpCenterCategory::unitedAdminUserCategory($request->order_by_field,$request->order_type)
            ->search($request->key)
            ->trashed($soft_delete)
            ->paginate(config('HelpCenter.panel.category_paginate_number'));
        return ApiResponseClass::successResponse(CategoryResource::collection($categories));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $helpCenter = HelpCenterCategory::with('UserGuides')
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new CategoryResource($helpCenter));

    }

    public function store(StoreCategoryRequest $request){
        $user = $request->user();
        $helpCenterCategoryData = CategoryData::fromRequest($request);
        $category = HelpCenterCategory::create($helpCenterCategoryData->all());
        return ApiResponseClass::successResponse(new CategoryResource($category));
    }

    public function show(Request $request,$id){
        $category = HelpCenterCategory::with('UserGuides')
        ->findOrFail($id);
        return ApiResponseClass::successResponse(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request,$categoryId){
        $user = $request->user();
        $helpCenterCategoryData = CategoryData::fromRequest($request);
        $category = HelpCenterCategory::findOrFail($categoryId);
        $category->update($helpCenterCategoryData->initializeForUpdate($helpCenterCategoryData));
        return ApiResponseClass::successResponse(new CategoryResource($category));
    }

    public function softDeleteOrRestore(Request $request,$categoryId){
        $user = $request->user();
        DB::beginTransaction();
        $category = HelpCenterCategory::withDeletedItems()
        ->findOrFail($categoryId);
        $category->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successMsgResponse();

    }

    public function destroy(Request $request,$categoryId){
        $user = $request->user();
        $category = HelpCenterCategory::withDeletedItems()
        ->findOrFail($categoryId);
        $category->delete();
        return ApiResponseClass::deletedResponse();

    }
}
