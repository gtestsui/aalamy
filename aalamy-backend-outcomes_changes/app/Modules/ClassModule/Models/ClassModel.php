<?php

namespace Modules\ClassModule\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ClassModule\Traits\ModelRelations\ClassRelations;

class ClassModel extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use ClassRelations;

    protected $table = 'classes';

    public static function customizedBooted(){}


    protected $fillable=[
        'level_id',
        'name',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    private $mySearchableFields = [
        'name',
    ];

    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'ClassInfos',
        'ClassStudents',

    ];


    //Attributes




    //Scopes

}
