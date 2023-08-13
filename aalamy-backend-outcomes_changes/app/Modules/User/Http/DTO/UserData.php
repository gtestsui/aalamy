<?php


namespace Modules\User\Http\DTO;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\FileManagmentServicesClass;
use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\Services\AuthServices\AuthByServiceFactory;
use Modules\User\Http\Controllers\Classes\UserServices;

final class UserData extends ObjectData
{
    public ?int      $id=null;
    public string    $fname;
    public string    $lname;
    public ?string    $email;
    public ?string   $password;
    public ?string   $image;
    public  string   $gender;
    public  Carbon   $date_of_birth;
    public  string   $account_type;
    public  string   $account_id;
    public ?string   $phone_code;
    public ?string   $phone_iso_code;
    public ?string   $phone_number;
    public ?string   $device_type;
    public ?string   $device_mac;
    public ?string   $login_service_id;
    public ?string   $login_service_avatar;
    public ?int      $address_id;
    public ?int      $country_id;
    public ?int      $state_id;
    public ?string   $city;
    public ?string   $street;
    public ?string   $firebase_token;
    //we fill this while create the user because we just initialize it
    public ?string   $unique_username;

    private ?bool    $permissionToUpdateEmail=false;
    private ?bool    $permissionToUpdatePassword=false;

    public static function fromRequest(Request $request,$account_type,$service=null): self
    {
        $imagePath = null;
        $fullName = getFullNameSeperatedByDash($request->fname, $request->lname);

        $email = $request->email;
        $loginServiceId = $request->login_service_id;
        if(isset($service)){
            $authData = AuthData::fromRequest($request);
            $authByServiceClass = AuthByServiceFactory::create($service,$authData);
            $email = $authByServiceClass->getEmail();
            $loginServiceId = $authByServiceClass->getId();//to get the real id if the user have sent one doesn't belong to hi,
        }

        if(isset($request->image))
            $imagePath = FileManagmentServicesClass::storeBase64File($request->image,"profiles/{$fullName}",$fullName);
//            $imagePath = FileManagmentServicesClass::storeFiles($request->image,"profiles/{$fullName}",$fullName);
        return new self([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $email,
            //register by service or on update the password will be null
            'password' => is_null($service)?$request->password:null,
            //check if the image from service or from our site
            'image' => isset($request->login_service_avatar)?$request->login_service_avatar:$imagePath,
//            'account_type' => $request->account_type,
            'account_type' => $account_type,
            'account_id' => UserServices::getAccountId($service),
            'phone_code' => isset($request->phone_number)?$request->phone_code:null,
            'phone_iso_code' => isset($request->phone_number)?strtolower($request->phone_iso_code):null,
            'phone_number' => isset($request->phone_number)?$request->phone_number:null,
            'gender' => $request->gender,
            'date_of_birth' => Self::generateCarbonObject($request->date_of_birth,true),
            'device_type' => $request->device_type,
            'device_mac' => substr(exec('getmac'), 0, 17)/*$request->device_mac*/,
            'login_service_id' => $loginServiceId,
            'firebase_token' => $request->firebase_token,
//            'addressData' => $addressData,

            'country_id' => isset($request->country_id)?(int)$request->country_id:null,
            'state_id'   => isset($request->state_id)?(int)$request->state_id:null,
//            'city_id'    => isset($request->city_id)?(int)$request->city_id:null,
            'city'    => $request->city,
            'street'     => $request->street,


        ]);
    }


    public function initializeForUpdate(?ObjectData $data=null){
        $arrayUpdate = [];
        foreach ($this->all() as $key=>$element){

            //to delete the phone value if he wants(by send it null or empty)
            if($key=='phone_code' || $key=='phone_iso_code' || $key=='phone_number'){
                $arrayUpdate[$key]=$element;
            }

            if(isset($element) && $key!='password' && $key!='unique_username'){
                if( ($key=='email' && !$this->hasUpdateEmailPermission())
                    || ($key=='password' && !$this->hasUpdatePasswordPermission())
                ){
                    continue;
                }

                $arrayUpdate[$key]=$element;
            }
        }
        return $arrayUpdate;
    }

    public function allowToUpdateTheEmail(bool $allow=false){
        $this->permissionToUpdateEmail = $allow;
        return $this;
    }


    public function hasUpdateEmailPermission(){
        return $this->permissionToUpdateEmail;
    }


    public function allowToUpdateThePassword(bool $allow=false){
        $this->permissionToUpdatePassword = $allow;
        return $this;
    }

    public function hasUpdatePasswordPermission(){
        return $this->permissionToUpdatePassword;
    }

}
