<?php

namespace Modules\SubscriptionPlan\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;


    public static function customizedBooted(){}


    protected $fillable = [
        'identify',
        'name',
        'description',
        'type',
        'usage_type',//by_use(can use or not) or by_limit_number(there is a defined size of it)
//        'number',
        'is_active',
    ];

    //Scopes
    public function scopeActive($query,$status=true){
        return $query->where('is_active',$status);
    }


    //Relations
    public function SubscriptionPlanModules(){
        return $this->hasMany('Modules\SubscriptionPlan\Models\SubscriptionPlanModule','module_id');
    }



}
