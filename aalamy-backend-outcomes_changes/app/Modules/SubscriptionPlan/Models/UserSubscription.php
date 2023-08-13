<?php

namespace Modules\SubscriptionPlan\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;


    public static function customizedBooted(){

    }


    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'is_active',
        'is_confirmed',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    public $mySearchableFields = [
        'start_date',
        'end_date',
    ];

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function SubscriptionPlan(){
        return $this->belongsTo('Modules\SubscriptionPlan\Models\SubscriptionPlan','subscription_plan_id');
    }


    public function scopeActive($query,$status=true){
        return $query->where('is_active',$status);
    }

    public function scopeConfirmed($query,$status=true){
        return $query->where('is_confirmed',$status);
    }

    public function scopeAvailable($query){
        return $query->whereDate('end_date','>=',Carbon::now());

    }

    //Function
    public function isConfirmed(){
        return $this->is_confirmed?true:false;
    }






}
