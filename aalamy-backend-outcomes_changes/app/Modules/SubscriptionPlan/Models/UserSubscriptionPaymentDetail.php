<?php

namespace Modules\SubscriptionPlan\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscriptionPaymentDetail extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;


    public static function customizedBooted(){

    }


    protected $fillable = [
        'user_subscription_id',
        'payment_id',
        'payer_id',
        'payer_email',
        'payer_name',
        'cart',
        'amount',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    public function UserSubscription(){
        return $this->belongsTo(UserSubscription::class,'user_subscription_id');
    }







}
