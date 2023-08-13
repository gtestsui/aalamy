<?php

namespace Modules\TeacherPermission\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
//    use QuizRelations;

    protected $table = 'permissions';

    public static function customizedBooted(){}


    protected $fillable=[
        'name',
        'num',

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

    public function PermissionTeachers(){
        return $this->hasMany(PermissionTeacher::class,'permission_id');
    }

    //Attributes



    //Scopes


}
