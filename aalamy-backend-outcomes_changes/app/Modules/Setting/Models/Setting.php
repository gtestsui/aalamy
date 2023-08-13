<?php

namespace Modules\Setting\Models;

use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\Classes\FileSystemServicesClass;
use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    protected $table = 'settings';

    public static function customizedBooted(){}


    protected $fillable=[
        'logo',
    ];

    //Attributes
    public function getLogoAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return baseRoute()
                .FileSystemServicesClass::getDefaultStoragePathInsideDisk()
                .configFromModule('panel.logo_inner_path',ApplicationModules::SETTING_MODULE_NAME);
    }

    //Relations

    //Scopes

}
