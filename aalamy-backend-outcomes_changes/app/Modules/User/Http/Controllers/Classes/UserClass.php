<?php


namespace Modules\User\Http\Controllers\Classes;


use App\Exceptions\ErrorMsgException;
use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Address\Http\Controllers\Classes\AddressServices;
use Modules\Address\Models\Address;
use Modules\User\Http\Controllers\Classes\Services\AuthServices\AuthByServiceFactory;
use Modules\User\Http\Controllers\Classes\Services\LoggedDeviceServices;
use Modules\User\Http\Controllers\Classes\Services\RegisterServices;
use Modules\User\Http\DTO\AuthData;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\User;

class UserClass
{

    /**
     * @var bool $permissionToUpdateEmail to give permission to update the email
     */
    protected bool $permissionToUpdateEmail=false;
    /**
     * @var bool $permissionToUpdatePassword to give permission to update the password
     */
    protected bool $permissionToUpdatePassword=false;

    /**
     * @param bool $withObserve when its true so the UserObserver will working
     * (when user register by normal way)
     * but when another role add new user so we stop UserObserver to prevent send
     * verification code to email and make the account verified by default
     */
    public function createUser(UserData $userData,bool $withObserve=true):User
    {
        /**
         * @var $addressId int or null
         * if the country id is present that mean the addressId is int
         * else null
         */
        $addressId = AddressServices::createAddress(
            $userData->country_id,
            $userData->state_id,
//            $userData->city_id,
            $userData->city,
            $userData->street
        );

        $userData->merge([
            'address_id'=>$addressId,
            'unique_username' => UserServices::generateUniqueGuide()
        ]);
        if($withObserve){
            $user = User::create($userData->all());

        }else{
            $user = User::withoutEvents(function ()use ($userData){
                return User::create(array_merge($userData->all(),
                    ['verified_status'=>1]));
            });

        }

        $user = User::find($user->id);
        $user[config('User.panel.auth_token_name')] = $user->createToken('token')->accessToken;

        return $user;
    }

    /**
     * by default the user can't update the email whereas the admin can do
     * so @param bool $allow if its sent true then the email will updated
     */
    public function allowToUpdateTheEmail(bool $allow=true){
        $this->permissionToUpdateEmail = $allow;
        return $this;
    }

    /**
     * by default the user can't update the email whereas the admin can do
     * so @param bool $allow if its sent true then the email will updated
     */
    public function allowToUpdateThePassword(bool $allow=true){
        $this->permissionToUpdatePassword = $allow;
        return $this;
    }

    public function updateUser(UserData $userData,User $user):User
    {
        $user->load('Address');
        $arrayForUpdate = $userData
            ->allowToUpdateTheEmail($this->permissionToUpdateEmail)
            ->allowToUpdateThePassword($this->permissionToUpdatePassword)
            ->initializeForUpdate($userData);

        /**
         * if you want to update school_address then should send
         * country_id and the other data (country_id,..)
         */
        $address = $user->Address;
        if(isset($userData->country_id) && !is_null($address)){
            $addressId = AddressServices::updateOrCreateAddress(
                            $userData->country_id,
                            $userData->state_id,
                            $userData->city,
                            $userData->street,
                            $address
                        );
            $arrayForUpdate = array_merge($arrayForUpdate,['address_id'=>$addressId]);

        }
        $user->update($arrayForUpdate);
        //we used refresh to reload the relation in address(country,city,...)
        return $user->refresh('Address');
    }

    public function updatePassword(User $user,$oldPassword,$newPassword):User
    {
        $check = auth()->guard('web')->attempt([
            'email'=>$user->email,'password'=>$oldPassword
        ]);
        if(!$check)
            throw new ErrorMsgException(transMsg('wrong_credentials',ApplicationModules::USER_MODULE_NAME));
        $user->password = $newPassword;
        $user->save();
        return $user;
    }

    /**
     * @param  string $service this is the service we used to regsiter(google,...)
     * if its null that mean we use normal register(by email)
     * @return User
     */
    public function login(AuthData $authData,$service=null):User
    {
//        if(is_null($service)){
////            $credentials = request(['email', 'password']);
//            $credentials = [
//                'email' => $authData->email,
//                'password' => $authData->password,
//            ];
//            $user = RegisterServices::checkRegisteredAccount($credentials);
//        }else{
//            $authByServiceClass = AuthByServiceFactory::create($service,$authData);
//            $user = $authByServiceClass->checkRegisteredAccount();
////            $user = RegisterServices::checkRegisteredAccountByService($request->login_service_id,$request->email,$service);
//        }

        $authByServiceClass = AuthByServiceFactory::create($service,$authData);
        $user = $authByServiceClass->checkRegisteredAccount();

        LoggedDeviceServices::checkLoggedDevicesCount($user->id);

        $user[config('User.panel.auth_token_name')] = $user->createToken('token')->accessToken;
        $user->load(ucfirst($user->account_type));
        FirebaseServices::saveFirebaseToken($user,$authData->firebase_token,$authData->lang);
        LoggedDeviceServices::addLoggedDevice($user->id,$authData->device_type,substr(exec('getmac'), 0, 17));
        return $user;
    }

    /**
     * @var string $service this is the service we used to regiter(google,...)
     * if its null that mean we use normal registering
     */
    public function register(FormRequest $request,$accountType,$service=null):User
    {
        $userData = UserData::fromRequest($request,$accountType,$service);
        $childOfUserClassByType = UserServices::getObjectFromUserClassChildByType($accountType);
        $requestDataByType = $childOfUserClassByType->getDataFromRequest($request,$userData);
        $user = $childOfUserClassByType->create($requestDataByType,$userData);

        LoggedDeviceServices::addLoggedDevice($user->id,$userData->device_type,$userData->device_mac);
        FirebaseServices::saveFirebaseToken($user,$userData->firebase_token,$request->lang);
        return $user;

    }
}
