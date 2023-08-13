<?php

namespace Modules\Level\Http\Controllers\SuperAdminControllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\Level\Http\Requests\BaseLevelSubjectRule\StoreLevelSubjectRulesRequest;
use Modules\Level\Http\Requests\BaseLevelSubjectRule\UpdateLevelSubjectRulesRequest;
use Modules\Level\Http\Resources\BaseLevelSubjectRuleResource;
use Modules\Level\Models\BaseLevelSubjectRule;
use Modules\Level\Models\BaseLevelSubject;
use Modules\Level\Models\BaseSubject;

class Super_BaseLevelSubjectRuleController extends Controller
{



    public function showByBaseLevelSubjectId($base_level_subject_id){
        $baseLevelSubjectRule = BaseLevelSubjectRule::where('base_level_subject_id',$base_level_subject_id)
            ->first();
        if(is_null($baseLevelSubjectRule)){
            return  ApiResponseClass::successResponse(null);
        }
        return ApiResponseClass::successResponse(
            new BaseLevelSubjectRuleResource($baseLevelSubjectRule)
        );

    }


 public function storeOrUpdateAll(){
 		
  		$baseLevelSubjects = BaseLevelSubject::all();
 		foreach($baseLevelSubjects as $base )
        {
                 $baseLevelSubjectRule = BaseLevelSubjectRule::create([
                'base_level_subject_id' => $base->id,
                'requires_failure' => 1,
                'enter_the_overall_total' => 1,
                'optional' => 0,
                'max_degree' => 100,
                'min_degree' => 1,
                'failure_point' => 16,
                'its_one_field' => 1,
                'classes_count_at_week' => 20,
            ]);
 
        }
        return ApiResponseClass::successResponse($baseLevelSubjects);
 
        // $baseLevelSubjectRule = BaseLevelSubjectRule::where('base_level_subject_id',$base_level_subject_id)
        //     ->first();
        // if(is_null($baseLevelSubjectRule)){
        //     $baseLevelSubjectRule = BaseLevelSubjectRule::create([
        //         'base_level_subject_id' => $base_level_subject_id,
        //         'requires_failure' => $request->requires_failure,
        //         'enter_the_overall_total' => $request->enter_the_overall_total,
        //         'optional' => $request->optional,
        //         'max_degree' => $request->max_degree,
        //         'min_degree' => $request->min_degree,
        //         'failure_point' => $request->failure_point,
        //         'its_one_field' => $request->its_one_field,
        //         'classes_count_at_week' => $request->classes_count_at_week,
        //     ]);
        // }else{
        //     $baseLevelSubjectRule->update([
        //         'requires_failure' => $request->requires_failure,
        //         'enter_the_overall_total' => $request->enter_the_overall_total,
        //         'optional' => $request->optional,
        //         'max_degree' => $request->max_degree,
        //         'min_degree' => $request->min_degree,
        //         'failure_point' => $request->failure_point,
        //         'its_one_field' => $request->its_one_field,
        //         'classes_count_at_week' => $request->classes_count_at_week,
        //     ]);
        // }

        // return ApiResponseClass::successResponse(
        //     new BaseLevelSubjectRuleResource($baseLevelSubjectRule)
        // );
    }




    public function storeOrUpdate(StoreLevelSubjectRulesRequest $request,$base_level_subject_id){
        $baseLevelSubjectRule = BaseLevelSubjectRule::where('base_level_subject_id',$base_level_subject_id)
            ->first();
        if(is_null($baseLevelSubjectRule)){
            $baseLevelSubjectRule = BaseLevelSubjectRule::create([
                'base_level_subject_id' => $base_level_subject_id,
                'requires_failure' => $request->requires_failure,
                'enter_the_overall_total' => $request->enter_the_overall_total,
                'optional' => $request->optional,
                'max_degree' => $request->max_degree,
                'min_degree' => $request->min_degree,
                'failure_point' => $request->failure_point,
                'its_one_field' => $request->its_one_field,
                'classes_count_at_week' => $request->classes_count_at_week,
            ]);
        }else{
            $baseLevelSubjectRule->update([
                'requires_failure' => $request->requires_failure,
                'enter_the_overall_total' => $request->enter_the_overall_total,
                'optional' => $request->optional,
                'max_degree' => $request->max_degree,
                'min_degree' => $request->min_degree,
                'failure_point' => $request->failure_point,
                'its_one_field' => $request->its_one_field,
                'classes_count_at_week' => $request->classes_count_at_week,
            ]);
        }

        return ApiResponseClass::successResponse(
            new BaseLevelSubjectRuleResource($baseLevelSubjectRule)
        );
    }

//    public function update(UpdateLevelSubjectRulesRequest $request,$id){
//        $baseLevelSubjectRule = BaseLevelSubjectRule::findOrFail($id);
//        $baseLevelSubjectRule->update([
//            'base_level_subject_id' => $request->base_level_subject_id,
//            'requires_failure' => $request->requires_failure,
//            'enter_the_overall_total' => $request->enter_the_overall_total,
//            'optional' => $request->optional,
//            'max_degree' => $request->max_degree,
//            'min_degree' => $request->min_degree,
//            'failure_point' => $request->failure_point,
//            'its_one_field' => $request->its_one_field,
//            'classes_count_at_week' => $request->classes_count_at_week,
//        ]);
//        return ApiResponseClass::successResponse(
//            new BaseLevelSubjectRuleResource($baseLevelSubjectRule)
//        );
//    }




}
