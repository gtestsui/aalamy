<?php

namespace Modules\Address\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;

    public static function customizedBooted(){}


    protected $fillable=[
      'name_en',
      'name_ar',
    ];

    //Attributes

    //Relations
    public function States(){
        return $this->hasMany('Modules\Address\Models\State','country_id');
    }


    //Scopes
}
