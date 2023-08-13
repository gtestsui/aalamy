<?php

namespace Modules\Notification\Models;

use App\Http\Traits\DefaultGlobalScopes;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    protected $table = 'notifications_types';

    public static function customizedBooted(){}


    protected $fillable=[
        'name_en',
        'name_ar',
        'type_num',
    ];

    public function Notification(){
        return $this->hasMany('App\Models\Notification');
    }
}
