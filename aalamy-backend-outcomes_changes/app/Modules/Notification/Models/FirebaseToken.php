<?php

namespace Modules\Notification\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseToken extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;

    public static function customizedBooted(){}


    protected $fillable=[
      'user_id',
      'token',
      'lang',
      'deleted',
      'deleted_by_cascade',
      'deleted_at',
    ];

    public function User(){
        return $this->belongsTo('Modules\User\Models\User');
    }
}
