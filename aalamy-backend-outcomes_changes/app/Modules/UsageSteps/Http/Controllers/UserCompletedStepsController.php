<?php

namespace Modules\UsageSteps\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Modules\UsageSteps\Models\UserCompletedStep;
use Modules\UsageSteps\Http\Requests\LastCompletedStep\GetMyLastCompletedStepRequest;
use Modules\UsageSteps\Http\Requests\LastCompletedStep\UpdateLastCompletedStepRequest;

class UserCompletedStepsController extends Controller
{


    public function getMyLastCompletedStep(GetMyLastCompletedStepRequest $request){
        $user = $request->user();
        $userCompletedStep = UserCompletedStep::where('user_id',$user->id)->first();

        return ApiResponseClass::successResponse([
            'last_completed_step_index' => !is_null($userCompletedStep)
                ?$userCompletedStep->last_step_index
                :0,
            'data' => isset($userCompletedStep)?$userCompletedStep->data:null
        ]);

    }

    public function updateLastCompletedStep(UpdateLastCompletedStepRequest $request){
        $user = $request->user();
        $userCompletedStep = UserCompletedStep::where('user_id',$user->id)->first();
        if(is_null($userCompletedStep)){
            UserCompletedStep::create([
               'user_id' => $user->id,
               'last_step_index' => $request->last_completed_step_index,
               'data' => $request->data,
            ]);
        }else{
            $userCompletedStep->update([
               'last_step_index' => $request->last_completed_step_index,
               'data' => $request->data,
            ]);
        }
        return ApiResponseClass::successMsgResponse();
    }



}
