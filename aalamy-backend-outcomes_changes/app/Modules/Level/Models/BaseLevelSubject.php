<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Traits\ModelRelations\LevelRelations;

class BaseLevelSubject extends Model
{
    use DefaultGlobalScopes;
//    use DefaultOrderBy;
    use HasFactory;
    use SoftDelete;
    use Searchable;

    protected $table = 'base_level_subjects';

    public static function customizedBooted(){}


    protected $fillable=[
        'base_level_id',
        'base_subject_id',
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
        'name',
    ];

    //Relations
    public function BaseLevel(){
        return $this->belongsTo(BaseLevel::class,'base_level_id');
    }

    public function BaseSubject(){
        return $this->belongsTo(BaseSubject::class,'base_subject_id');
    }

    public function Rule(){
        return $this->hasOne(BaseLevelSubjectRule::class,'base_level_subject_id');
    }

    //Attributes



    //Scopes

}
