<?php

namespace Modules\Address\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class Address extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;

    public static function customizedBooted(){}


    protected $fillable=[
      'country_id',
      'state_id',
//      'city_id',
      'city',
      'street',
    ];

    /**
     * this will always return this relation with address object
     */
    protected $with = array('Country', 'State'/*,'City'*/);

    //Attributes

    //Relations
    public function Country(){
        return $this->belongsTo('Modules\Address\Models\Country','country_id');
    }

    public function State(){
        return $this->belongsTo('Modules\Address\Models\State','state_id');
    }

    public function User(){
        return $this->hasOne(User::class,'user_id');
    }

//    public function City(){
//        return $this->belongsTo('Modules\Address\Models\City','city_id');
//    }


    //Scopes
}
