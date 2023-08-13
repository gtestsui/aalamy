<?php


namespace Modules\User\Http\Controllers\Classes\Services\AuthServices;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Http\DTO\AuthData;
use Modules\User\Models\User;

class Email implements AuthByServiceInterface
{

//    private $access_token ;
    private array  $credentials;
    private string $email;
    private string $password;
    public function __construct(AuthData $authData){

        $this->email = $authData->email;
        $this->password = $authData->password;
        $this->credentials = [
            'email' => $authData->email,
            'password' => $authData->password,
        ];


    }

    /**
     * @return string
     */
    public function getEmail(){
        return $this->email;
    }

    /**
     * @return null id the account in the service
     */
    public function getId(){
        return null;
    }


    /**
     * @return User
     * @throws ErrorMsgException
     */
    public function checkRegisteredAccount():User
    {

        if(!Auth::attempt($this->credentials))
            throw new ErrorMsgException(transMsg('wrong_credentials',ApplicationModules::USER_MODULE_NAME));

        $user = User::where(["email" => $this->email])->first();
        return $user;
    }


}
