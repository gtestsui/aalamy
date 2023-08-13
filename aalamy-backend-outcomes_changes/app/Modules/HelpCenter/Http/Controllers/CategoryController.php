<?php

namespace Modules\HelpCenter\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\HelpCenter\Http\Requests\Category\StoreCategoryRequest;
use Modules\HelpCenter\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Modules\HelpCenter\Http\Resources\CategoryResource;
use Modules\HelpCenter\Models\HelpCenterCategory;
use Modules\HelpCenter\Http\DTO\CategoryData;

class CategoryController extends Controller
{

    /**
     * get just the category that have user guides suitable with my account type
     */
    public function index(Request  $request){
        $user = $request->user();
        $categories = HelpCenterCategory::order($request->order_by_field,$request->order_type)
            ->with('UserGuides')
            ->whereHas('UserGuides',function ($query)use ($user){
                return $query->whereJsonContains('user_types',$user->account_type);
            })
            ->get();
        return ApiResponseClass::successResponse(CategoryResource::collection($categories));
    }

    /**
     * get just the category that have user guides suitable with my account type
     */
    public function paginate(Request $request){
        $user = $request->user();
        $categories = HelpCenterCategory::order($request->order_by_field,$request->order_type)
            ->with('UserGuides')
            ->whereHas('UserGuides',function ($query)use ($user){
               return $query->whereJsonContains('user_types',$user->account_type);
            })
            ->paginate(config('HelpCenter.panel.category_paginate_number'));
        return ApiResponseClass::successResponse(CategoryResource::collection($categories));
    }

}
