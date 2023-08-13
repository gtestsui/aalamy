<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Traits\ModelRelations\LevelRelations;

class BaseLevelSubjectRule extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;

    protected $table = 'base_level_subject_rules';

    public static function customizedBooted(){}


    protected $fillable=[
        'base_level_subject_id',

        'requires_failure',
        'enter_the_overall_total',
        'optional',
        'max_degree',
        'min_degree',
        'failure_point',
        'its_one_field',
        'classes_count_at_week',

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

    ];

    private $mySearchableFields = [

    ];


    //Relations
    public function BaseLevelSubject(){
        return $this->belongsTo(BaseLevelSubject::class,'base_level_subject_id');
    }

    //Attributes



    //Scopes

}
