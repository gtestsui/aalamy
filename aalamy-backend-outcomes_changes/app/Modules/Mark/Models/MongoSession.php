<?php

namespace Modules\Mark\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Event\Traits\ModelRelations\EventRelations;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoSession extends Model
{
    use HasFactory;

    protected $connection  = 'mongodb';
    protected $collection  = 'sessions';

    public static function customizedBooted(){}


    protected $fillable=[
        'username',
        'body',
        'assignment',
        'roster',
        'roster_assignment',
        'page',
        'user_id',
        'page_id',
    ];


    protected $dates = ['created_at', 'updated_at'];


    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [

    ];

    //Attributes




    //Scopes


}
