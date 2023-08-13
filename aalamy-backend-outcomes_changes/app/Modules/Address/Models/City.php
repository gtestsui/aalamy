<?php

namespace Modules\Address\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;

    public static function customizedBooted(){}


    protected $fillable=[
      'name_en',
      'name_ar',
      'state_id',
    ];

    //Attributes

    //Relations
    public function State(){
        return $this->belongsTo('Modules\Address\Models\State','state_id');
    }


    //Scopes
}
