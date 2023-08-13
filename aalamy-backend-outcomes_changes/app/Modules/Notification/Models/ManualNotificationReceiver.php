<?php

namespace Modules\Notification\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualNotificationReceiver extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;

    public static function customizedBooted(){}


    protected $fillable=[
      'user_id',
      'manual_notification_id',
    ];

    public function ManualNotification(){
        return $this->belongsTo('Modules\Notification\Models\ManualNotification','manual_notification_id');
    }

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }
}
