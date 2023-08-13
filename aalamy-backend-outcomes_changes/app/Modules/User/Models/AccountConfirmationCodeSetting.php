<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Traits\ModelRelations\AccountConfirmationCodeSettingRelations;
use Illuminate\Database\Eloquent\Model;


class AccountConfirmationCodeSetting extends Model
{
    use HasFactory;
    use ModelSharedScopes;
    use SoftDelete;
    use AccountConfirmationCodeSettingRelations;


    public static function customizedBooted(){}


    protected $fillable = [
        'user_id',
        'attempt_num',
        'allow_after_date',

        'deleted',
        'deleted_by_cascade',
        'deleted_at',

    ];

     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [

    ];


    //Attributes
    public function setAllowAfterDateAttribute($key){
        $this->attributes['allow_after_date'] = ServicesClass::toTimezone(
            $key,
            config('app.timezone'),
            'UTC'
        );
    }



}
