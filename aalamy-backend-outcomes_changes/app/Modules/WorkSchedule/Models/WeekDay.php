<?php

namespace Modules\WorkSchedule\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Roster\Traits\ModelRelations\RosterRelations;

class WeekDay extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use RosterRelations;
    protected $table = 'week_days';

    public static function customizedBooted(){}


    protected $fillable=[
        'name',
    ];



    //Attributes




    //Scopes



}
