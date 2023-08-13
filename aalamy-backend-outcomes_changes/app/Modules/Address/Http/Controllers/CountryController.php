<?php

namespace Modules\Address\Http\Controllers;

use App\Http\Controllers\Classes\ApiResponseClass;
use App\Http\Controllers\Classes\RequestServicesClass;
use App\Http\Controllers\Controller;
use Modules\Address\Http\DTO\CountryData;
use Modules\Address\Http\Requests\Country\StoreCountryRequest;
use Modules\Address\Http\Requests\Country\UpdateCountryRequest;
use Modules\Address\Models\Country;

class CountryController extends Controller
{


    public function index(){
        $countries = Country::get();
        return ApiResponseClass::successResponse($countries);
    }

    public function getCountryWithStates(){
        $countries = Country::with('States')
            ->get();
        return ApiResponseClass::successResponse($countries);
    }

    public function store(StoreCountryRequest $request){
        $countryData = CountryData::fromRequest($request);
        $country = Country::create($countryData->all());
        return ApiResponseClass::successResponse($country);
    }

    public function update(UpdateCountryRequest $request,$id){
        $country = Country::findOrFail($id);
        $countryData = CountryData::fromRequest($request);
        $country->update($countryData->initializeForUpdate($countryData));
        return ApiResponseClass::successResponse($country);
    }

    public function destroy($id){
        $country = Country::findOrFail($id);
        $country->delete();
        return ApiResponseClass::deletedResponse();
    }


}
