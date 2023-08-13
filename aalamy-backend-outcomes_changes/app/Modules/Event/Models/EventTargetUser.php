<?php

namespace Modules\Event\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Event\Traits\ModelRelations\EventTargetRelations;

class EventTargetUser extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use EventTargetRelations;

    protected $table = 'event_target_users';

    public static function customizedBooted(){}


    protected $fillable=[
        'event_id',
        'student_id',
        'teacher_id',
        'parent_id',
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




    //Scopes

}
