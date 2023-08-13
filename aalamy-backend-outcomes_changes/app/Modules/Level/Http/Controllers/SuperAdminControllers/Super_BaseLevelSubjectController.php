<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Level\Http\Requests\BaseLevelSubject\RelateBaseLevelWithBaseSubjectsRequest;
use Modules\Level\Models\BaseLevelSubject;
use Modules\Level\Models\BaseSubject;

class Super_BaseLevelSubjectController extends Controller
{


    public function paginate(){
        $baseLevelSubjects = BaseLevelSubject::with(['BaseSubject','BaseLevel'])
            ->orderBy('base_level_id','asc')
            ->paginate(10);
        return ApiResponseClass::successResponse($baseLevelSubjects);
    }

    public function getByBaseLevelId($base_level_id){
        $baseLevelSubjects = BaseLevelSubject::where('base_level_id',$base_level_id)
            ->with(['BaseSubject'])
            ->orderBy('base_level_id','asc')
            ->paginate(10);
        return ApiResponseClass::successResponse($baseLevelSubjects);

    }


    public function relateBaseLevelWithMultiBaseSubjects(RelateBaseLevelWithBaseSubjectsRequest $request,$base_level_id){

        DB::beginTransaction();
        //get just the subjects doesn't belong to the same level ,to prevent duplicate
        $baseSubjectsIds = BaseSubject::whereIn('id',$request->base_subjects_ids)
            ->whereDoesntHave('BaseLevelSubjects',function ($query)use ($base_level_id){
                return $query->where('base_level_id',$base_level_id);
            })
            ->pluck('id')
            ->toArray();
        foreach ($baseSubjectsIds as $subjectId){
            BaseLevelSubject::create([
                'base_level_id' => $base_level_id,
                'base_subject_id' => $subjectId,
            ]);
        }
        DB::commit();

        return ApiResponseClass::successMsgResponse();
    }





}
