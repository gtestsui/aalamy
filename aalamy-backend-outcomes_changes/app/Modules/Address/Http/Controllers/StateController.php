<?php

namespace Modules\Address\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Modules\Address\Http\DTO\StateData;
use Modules\Address\Http\Requests\State\StoreStateRequest;
use Modules\Address\Http\Requests\State\UpdateStateRequest;
use Modules\Address\Models\State;

class StateController extends Controller
{


    public function index(){
        $states = State::get();
        return ApiResponseClass::successResponse($states);
    }

    public function store(StoreStateRequest $request){
        $stateData = StateData::fromRequest($request);
        $state = State::create($stateData->all());
        return ApiResponseClass::successResponse($state);
    }

    public function update(UpdateStateRequest $request,$id){
        $state = State::findOrFail($id);
        $stateData = StateData::fromRequest($request);
        $state->update($stateData->initializeForUpdate($stateData));
        return ApiResponseClass::successResponse($state);
    }

    public function destroy($id){
        $state = State::findOrFail($id);
        $state->delete();
        return ApiResponseClass::deletedResponse();
    }


}
