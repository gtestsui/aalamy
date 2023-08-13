<?php

namespace Modules\Notification\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Modules\Notification\Traits\ModelRelations\ManualNotificationRelations;
use Modules\Notification\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualNotification extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use ManualNotificationRelations;

    public static function customizedBooted(){}


    protected $fillable=[
//      'user_id',//who add the notification
      'school_id',
      'teacher_id',
      'educator_id',
      'subject',
      'content',
      'priority',
      'send_by_types',
    ];

    //Attributes
    public function getSendByTypesAttribute($key){
        return json_decode($key);

    }


    public function setSendByTypesAttribute($value){

        $this->attributes['send_by_types'] = json_encode($value);

    }
}
