<?php

namespace Modules\SubscriptionPlan\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanModule extends Model
{
    use DefaultGlobalScopes;
    use  HasFactory;


    public static function customizedBooted(){}


    protected $fillable = [
        'subscription_plan_id',
        'module_id',
        'number',
        'can_use',

    ];

    public function SubscriptionPlan(){
        return $this->belongsTo('Modules\SubscriptionPlan\Models\SubscriptionPlan','subscription_plan_id');
    }

    public function Module(){
        return $this->belongsTo('Modules\SubscriptionPlan\Models\Module','module_id');
    }






}
