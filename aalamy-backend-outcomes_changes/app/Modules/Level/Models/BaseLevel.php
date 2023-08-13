<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Traits\ModelRelations\LevelRelations;

class BaseLevel extends Model
{
    use DefaultGlobalScopes;
//    use DefaultOrderBy;
    use HasFactory;
    use SoftDelete;
    use Searchable;

    protected $table = 'base_levels';

    public static function customizedBooted(){}


    protected $fillable=[
        'name',
        'is_default',//because
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
        'Levels',
        'BaseLevelSubjects'
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
    public function Levels(){
        return $this->hasMany(Level::class,'base_level_id');
    }

    public function BaseLevelSubjects(){
        return $this->hasMany(BaseLevelSubject::class,'base_level_id');
    }


    //Attributes



    //Scopes
    public function scopeIsDefault($query,$value=true){
        return $query->where('is_Default',$value);
    }

}
