<?php

namespace Modules\Address\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;

    public static function customizedBooted(){}


    protected $fillable=[
      'name_en',
      'name_ar',
      'country_id',
    ];

    //Attributes

    //Relations
    public function Country(){
        return $this->belongsTo('Modules\Address\Models\Country','country_id');
    }

    public function Cities(){
        return $this->hasMany('Modules\Address\Models\City','state_id');
    }


    //Scopes
}
