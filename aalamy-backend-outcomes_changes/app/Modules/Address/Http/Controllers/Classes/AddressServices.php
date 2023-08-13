<?php


namespace Modules\Address\Http\Controllers\Classes;


use Modules\Address\Http\DTO\AddressData;
use Modules\Address\Models\Address;

class AddressServices
{



    public static function createAddress(
            $country_id,
            $state_id,
            $city,
            $street){
        //if country is present that mean the address isn't null
        $addressId = null;

        if(isset($country_id)){
            $address =  Address::create([
                'country_id' => $country_id,
                'state_id' => $state_id,
//                'city_id' => $city_id,
                'city' => $city,
                'street' => $street,
            ]);
            $addressId = $address->id;
        }
        return $addressId;
    }

    public static function updateOrCreateAddress(
        $country_id,
        $state_id,
        $city,
        $street,
        ?Address $address=null
        ){

        if(is_null($address))
            return Self::createAddress($country_id,$state_id,$city,$street);

        $address->update([
            'country_id' => isset($country_id)?$country_id:$address->country_id,
            'state_id' => isset($state_id)?$state_id:$address->state_id,
//            'city_id' => isset($city_id)?$city_id:$address->city_id,
            'city' => isset($city)?$city:$address->city,
            'street' => isset($street)?$street:$address->street,
        ]);
        return $address->id;
    }

}
