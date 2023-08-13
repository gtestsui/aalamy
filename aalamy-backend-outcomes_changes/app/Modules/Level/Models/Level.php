<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Traits\ModelRelations\LevelRelations;

class Level extends Model
{
    use DefaultGlobalScopes;
//    use DefaultOrderBy;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use LevelRelations;

    protected $table = 'levels';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'base_level_id',
        'name',
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
        'Classes',
        'LevelSubjects',
        'StudentStudyingInformation',

    ];


    /**
     * @var string[] $parentRelations
     * when the model belongs to another  parent model
     * and the model and his parent are deleted
     * andddd I can't restore the model if the parent is deleted
     * then I should fill $parentRelations array by
     * the relation name to that parent model
     * to prevent restore that model
     */
    protected $parentRelations = [
      'User'
    ];

    private $mySearchableFields = [
        'name',
        'type',
        'created_at',
    ];

    //Attributes



    //Scopes

}
