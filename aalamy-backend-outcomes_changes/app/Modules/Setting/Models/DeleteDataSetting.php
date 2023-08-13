<?php

namespace Modules\Setting\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteDataSetting extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    protected $table = 'delete_data_settings';

    public static function customizedBooted(){}


    protected $fillable=[
        'time_for_force_delete_data',
        'type',
    ];

    //Attributes


    //Relations

    //Scopes

}
