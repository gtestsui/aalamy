<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorUnAuthorizationException;
use App\Modules\User\Http\DTO\SchoolData;
use Illuminate\Http\Request;
use Modules\Address\Http\Controllers\Classes\AddressServices;
use Modules\Address\Models\Address;
use Modules\Level\Http\Controllers\Classes\LevelServices;
use Modules\SubscriptionPlan\Http\Controllers\Classes\UserSubscribeClass;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\School;
use Modules\User\Models\User;

class SchoolClass extends UserClass
{

    public function getDataFromRequest(Request $request,UserData $userData=null):SchoolData
    {
        $data = SchoolData::fromRequest($request);
        return $data;
    }

    /**
     * when the school its created by default we create subscribe in free plan
     */
    public function create(SchoolData $schoolData, UserData $userData):User
    {
        $user = Parent::createUser($userData);

        /**
         * @var $addressId int or null
         * if the school_country_id is present that mean the addressId
         * is int else null
         */
        $addressId = AddressServices::createAddress(
            $schoolData->school_country_id,
            $schoolData->school_state_id,
//            $schoolData->school_city_id,
            $schoolData->school_city,
            $schoolData->school_street
        );

        $school = School::create([
            'user_id' => $user->id,
            'school_name' => $schoolData->school_name,
            'school_image' => $schoolData->school_image,
            'bio' => $schoolData->bio,
            'address_id' => $addressId,
        ]);

        $userSubscribeClass = new UserSubscribeClass($user);
        $userSubscribeClass->subscribeSchoolFreePlan();

        $user->load('School');
        LevelServices::createDefaultEducationalContent($user);


        return $user;
    }

    /**
     * we used school_address_id_for_update and address_id_for_update
     * because maybe the admin school want to update his address adn his school address
     * in same request
     *
     */
    public function update(SchoolData $schoolData,User $user):User
    {
        $user->load('School.Address');

        $school = $user->School;
        $schoolArrayForUpdate = $schoolData->initializeForUpdate($schoolData);

        /**
         * if you want to update school_address then should send
         * school_country_id and the other data (country_id,..)
         */
        $address = $user->School->Address;
        if(isset($schoolData->school_country_id) && !is_null($address)){
            $addressId = AddressServices::updateOrCreateAddress(
                            $schoolData->school_country_id,
                            $schoolData->school_state_id,
                            $schoolData->school_city,
                            $schoolData->school_street,
                            $address
                        );
            $schoolArrayForUpdate = array_merge($schoolArrayForUpdate,['address_id'=>$addressId]);

        }

        $school->update($schoolArrayForUpdate);

        //we used refresh to reload the relation in address(country,city,...)
        $user->School->refresh('Address');
        return $user;
    }


    public function updateAccountWithPersonalInfo(SchoolData $schoolData, UserData $userData,User $user):User
    {
        $user = Parent::updateUser($userData,$user);
        $user = $this->update($schoolData,$user);
        return  $user;
    }


//    public function search($key,$order_by_field,$order_type){
//        $userFields = User::getSearchableFields();
//        $schoolFields = ['School.[school_name,bio]'];
//        $allFields = array_merge($userFields,$schoolFields);
//        $users = User::where('account_type','school')
//            ->search($key,$allFields)
//            ->with('School')
//            ->order($order_by_field,$order_type)
//            ->get();
//        return $users;
//    }


}
