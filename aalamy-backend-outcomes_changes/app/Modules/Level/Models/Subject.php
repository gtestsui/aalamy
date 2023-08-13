<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\Level\Traits\ModelRelations\SubjectRelations;

class Subject extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use ModelSharedScopes;
    use SubjectRelations;
    protected $table = 'subjects';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'base_subject_id',
        'name',
        'semester',
        'code',
        'type',
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
        'LevelSubjects',

    ];


    private $mySearchableFields = [
        'name',
        'type',
    ];

    //Attributes




    //Scopes

}
