<?php

namespace Modules\SubscriptionPlan\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use DefaultGlobalScopes;
    use Searchable;
    use HasFactory;


    public static function customizedBooted(){}


    protected $fillable = [
        'name',
        'description',
        'is_paid',
        'deleted',
        'cost',
        'billing_cycle',
        'billing_cycle_days',
        'type',//school or educator
        'is_active',
    ];

    public $mySearchableFields = [
        'name',
        'description',
        'cost',
        'billing_cycle',
    ];

    //Scopes
    public function scopeActive($query,$status=true){
        return $query->where('is_active',$status);
    }
    /*public function scopeActive($query,$ignoreSoftDelete=0){
        if(!$ignoreSoftDelete)
            return $query->where('deleted','!=',1);
        else
            return $query;
    }*/

    public function scopePaid($query,$is_paid=true){
        return $query->where('is_paid',$is_paid);
    }

    //Relations
    public function SubscriptionPlanModules(){
        return $this->hasMany('Modules\SubscriptionPlan\Models\SubscriptionPlanModule','subscription_plan_id')
            ->orderBy('number','desc');
    }

    //Functions
    public function activateOrUnActivate(){
        $this->update([
            'is_active'=> !$this->is_active
        ]);
    }






}
