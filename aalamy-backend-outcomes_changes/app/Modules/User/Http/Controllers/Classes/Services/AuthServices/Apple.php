<?php


namespace Modules\User\Http\Controllers\Classes\Services\AuthServices;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Http\DTO\AuthData;
use Modules\User\Models\User;

class Apple implements AuthByServiceInterface
{

//    private $access_token ;
    private ?\Laravel\Socialite\Two\User $info;
    public function __construct(AuthData $authData){
//        $this->access_token = $access_token;

        try {
            $this->info = Socialite::driver('apple')
                ->userFromToken($authData->service_access_token);

        }catch (\Exception $e){
            $this->info = null;
            throw new ErrorMsgException('some thing went wrong with apple account');
        }

    }


    /**
     * @return string
     */
    public function getEmail(){
        return $this->info->getEmail();
    }

    /**
     * @return string id the account in the service
     */
    public function getId(){
        return $this->info->getId();
    }

    /**
     * @return User
     * @throws ErrorMsgException
     */
    public function checkRegisteredAccount():User
    {

        $user = User::where('login_service_id',$this->getId())
//            ->where('email',$this->getEmail())
            ->where('account_id',config('User.panel.login_services.apple'))
            ->first();
        if(is_null($user))
            throw new ErrorMsgException(transMsg('wrong_credentials',ApplicationModules::USER_MODULE_NAME));
        return $user;
    }


}
