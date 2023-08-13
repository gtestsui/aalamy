<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Traits\ModelRelations\LevelRelations;

class BaseSubject extends Model
{
    use DefaultGlobalScopes;
//    use DefaultOrderBy;
    use HasFactory;
    use SoftDelete;
    use Searchable;

    protected $table = 'base_subjects';

    public static function customizedBooted(){}


    protected $fillable=[
        'name',
        'semester',
        'code',
        'hyperlink',
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
        'Subjects',
        'BaseLevelSubjects',
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
    public function Subjects(){
        return $this->hasMany(Subject::class,'base_subject_id');
    }

    public function BaseLevelSubjects(){
        return $this->hasMany(BaseLevelSubject::class,'base_subject_id');
    }


    public function BaseSubject(){
        return $this->belongsTo(BaseSubject::class,'base_subject_id');
    }

    //Attributes



    //Scopes

}
