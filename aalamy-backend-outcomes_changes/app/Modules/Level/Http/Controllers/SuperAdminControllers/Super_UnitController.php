<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Resources\UnitResource;
use Modules\Level\Models\Unit;

class Super_UnitController extends Controller
{


    public function paginate(Request $request,$soft_delete=null){
        $user = $request->user();
        DB::connection()->enableQueryLog();

        $units = Unit::search($request->key,[],[
                'LevelSubject'=>['Subject','Level'],
                'User'
            ])
            ->trashed($soft_delete)
            ->with(['User'=>function($query){
                return $query->with('Educator','School');
            },'LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            }])
            ->paginate(config('panel.admin_paginate_num'));
//                return(DB::getQueryLog());

        return ApiResponseClass::successResponse(UnitResource::collection($units));
    }

    public function getElementByIdEvenItsDeleted(Request $request,$id,$soft_delete){
        $unit = Unit::with(['User'=>function($query){
                return $query->with('Educator','School');
            },'LevelSubject'=>function($query){
                return $query->with(['Level','Subject']);
            }])
            ->findOrFail($id);

        return ApiResponseClass::successResponse(new UnitResource($unit));

    }

    public function getByLevelSubjectId(Request $request,$level_subject_id){
        $units = Unit::where('level_subject_id',$level_subject_id)
            ->get();

        return ApiResponseClass::successResponse(UnitResource::collection($units));
    }

    public function softDeleteOrRestore(Request $request,$unit_id){
        DB::beginTransaction();
        $unit = Unit::withDeletedItems()
            ->findOrFail($unit_id);
        $unit->softDeleteOrRestore();
        DB::commit();
        return ApiResponseClass::successResponse($unit);

    }

    public function destroy(Request $request,$unit_id){
        $unit = Unit::withDeletedItems()
            ->findOrFail($unit_id);
        $unit->delete();
        return ApiResponseClass::deletedResponse();

    }




}
