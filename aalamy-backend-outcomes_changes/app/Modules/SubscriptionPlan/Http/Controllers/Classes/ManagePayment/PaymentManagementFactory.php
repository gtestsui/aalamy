<?php

namespace Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment;

use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\AbstractManagementFactory;
use Modules\SubscriptionPlan\Http\Controllers\Classes\ManagePayment\PaypalClass;
use Modules\User\Models\User;

class PaymentManagementFactory
{

    /**
     * the key should start with lowercase letter
     * and the value the path of the target class
     */
    protected static $paths = [
        'paypal' => PaypalClass::class,
    ];

    /**
     * return the array or just one item depends on key
     */
    public static function supportedClasses($key=null){
        return isset($key)
            ?static::$paths[$key]
            :static::$paths;
    }

    public static function create($paymentMethod=null):ManagePaymentInterface{


//        $paypalClass = new PaypalClass();
//        return $paypalClass;

        if(!key_exists($paymentMethod,static::supportedClasses()))
            throw new ErrorMsgException('trying to declare invalid class type ');

        $classPath = static::supportedClasses($paymentMethod);
        if(class_exists($classPath)){
            return new $classPath();
        }
        throw new ErrorMsgException('trying to declare invalid class type ');
    }


}
