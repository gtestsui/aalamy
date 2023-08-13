<?php

namespace Modules\TeacherPermission\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\TeacherPermission\Traits\ModelRelations\TeacherPermissionRelations;

class PermissionTeacher extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use TeacherPermissionRelations;

    protected $table = 'permission_teachers';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'teacher_id',
        'permission_id',

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

    protected $mySearchableFields = [


    ];

    //Attributes



    //Scopes


}
