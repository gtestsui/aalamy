<?php

namespace Modules\Notification\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Modules\Notification\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use DefaultGlobalScopes;
    use HasFactory,SetTimeZone;

    public static function customizedBooted(){}


    protected $fillable=[
      'type_id',
      'user_id',
      'data',
      'read_date',
      'is_seen'
    ];

    public function Type(){
        return $this->belongsTo(NotificationType::class,'type_id');
    }

    //Attribute
    public function getDataAttribute($key){
        return json_decode($key);
    }
}
