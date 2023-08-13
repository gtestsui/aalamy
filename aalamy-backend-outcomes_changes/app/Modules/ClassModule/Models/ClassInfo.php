<?php

namespace Modules\ClassModule\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ClassModule\Traits\ModelRelations\ClassInfoRelations;
use Modules\Roster\Models\Roster;

class ClassInfo extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use ClassInfoRelations;

    protected $table = 'class_infos';

    public static function customizedBooted(){}


    protected $fillable=[
        'class_id',
        'school_id',
        'level_subject_id',
        'teacher_id',
        'educator_id',
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
        'Rosters',

    ];

    //Attributes



    //Scopes

}
