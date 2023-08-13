<?php

namespace Modules\Event\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Event\Traits\ModelRelations\EventRelations;
use Modules\User\Http\Controllers\Classes\UserServices;

class Event extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use EventRelations;

    protected $table = 'events';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'educator_id',
        'teacher_id',
        'name',
        'date',
//        'date',
//        'time',
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
        'TargetUsers',

    ];

    protected $mySearchableFields = [
        'name',
        'date',
    ];

    //Attributes




    //Scopes
    public function scopeIsTargeteMe($query,$accountType,$accountObject){
        return $query->whereHas('TargetUsers',function ($query)use($accountType,$accountObject){
            return $query->where("{$accountType}_id",$accountObject->id);
        });

    }

    public function scopeByMonth($query,Carbon $date){
        return $query->whereMonth('date',$date->month);
    }

    public function scopeByDay($query,Carbon $date){
        return $query->whereDay('date',$date->day);
    }


    public function scopeBelongsToMe($query,$accountType,$accountObject){
        return $query->where("{$accountType}_id",$accountObject->id);

    }

    public function scopeWithAllRelations($query){
        return $query->with(['Educator.User','School.User','Teacher.User']);

    }

}
