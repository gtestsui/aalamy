<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Level\Http\Requests\BaseLevel\StoreBaseLevelRequest;
use Modules\Level\Http\Requests\baseLevel\UpdateBaseLevelRequest;
use Modules\Level\Models\BaseLevel;

class Super_BaseLevelController extends Controller
{

    public function index(){
        $levels = BaseLevel::all();
        return ApiResponseClass::successResponse($levels);
    }

    public function store(StoreBaseLevelRequest $request){
        $level = BaseLevel::create([
            'name' => $request->name
        ]);
        return ApiResponseClass::successResponse($level);
    }

    public function update(UpdateBaseLevelRequest $request,$id){
        $level = BaseLevel::find($id);
        $level->update([
            'name' => $request->name
        ]);
        return ApiResponseClass::successResponse($level);
    }



}
