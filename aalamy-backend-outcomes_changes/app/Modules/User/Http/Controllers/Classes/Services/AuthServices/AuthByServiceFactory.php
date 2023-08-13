<?php


namespace Modules\User\Http\Controllers\Classes\Services\AuthServices;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Modules\User\Http\DTO\AuthData;
use Modules\User\Models\User;

class AuthByServiceFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    private static $paths = [
        'google' => Google::class,
        'email' => Email::class,
        'apple' => Apple::class,
    ];

    /**
     * @param string $service
     * @param string $accessToken
     * @return AuthByServiceInterface
     * @throws ErrorMsgException
     */
    public static function create($service,AuthData $authData):AuthByServiceInterface{

        $service = $service??'email';

        if(!key_exists($service,self::$paths))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = self::$paths[$service];
        if(class_exists($classPath)){
            return new $classPath($authData);
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}
