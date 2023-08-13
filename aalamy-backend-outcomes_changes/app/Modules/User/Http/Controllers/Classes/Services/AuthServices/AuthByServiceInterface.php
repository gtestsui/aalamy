<?php


namespace Modules\User\Http\Controllers\Classes\Services\AuthServices;


use App\Exceptions\ErrorMsgException;
use Modules\User\Models\User;

interface AuthByServiceInterface
{


    /**
     * @return string
     */
    public function getEmail();
    /**
     * return null if we have logged in using service from system (email,phone,..)
     * and return string if logged in using 3rd party service (google,facebook,..)
     * @return string|null id the account in the service(google,facebook,..)
     */
    public function getId();

    /**
     * @return User
     * @throws ErrorMsgException
     */
    public function checkRegisteredAccount():User;



}
