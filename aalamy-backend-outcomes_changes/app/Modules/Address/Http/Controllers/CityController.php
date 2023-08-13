<?php

namespace Modules\Address\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Modules\Address\Http\DTO\CityData;
use Modules\Address\Http\Requests\City\StoreCityRequest;
use Modules\Address\Http\Requests\City\UpdateCityRequest;
use Modules\Address\Models\City;

class CityController extends Controller
{


    public function index(){
        $cities = City::get();
        return ApiResponseClass::successResponse($cities);
    }

    public function store(StoreCityRequest $request){
        $cityData = CityData::fromRequest($request);
        $city = City::create($cityData->all());
        return ApiResponseClass::successResponse($city);
    }

    public function update(UpdateCityRequest $request,$id){
        $city = City::findOrFail($id);
        $cityData = CityData::fromRequest($request);
        $city->update($cityData->initializeForUpdate($cityData));
        return ApiResponseClass::successResponse($city);
    }

    public function destroy($id){
        $city = City::findOrFail($id);
        $city->delete();
        return ApiResponseClass::deletedResponse();
    }


}
