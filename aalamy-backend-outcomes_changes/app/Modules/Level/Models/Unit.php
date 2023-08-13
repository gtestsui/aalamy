<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Modules\Level\Traits\ModelRelations\UnitRelations;

class Unit extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use ModelSharedScopes;
    use UnitRelations;
    protected $table = 'units';

    public static function customizedBooted(){
//        dump('unit');
//        dump(request());
//        dump(request()->route('soft_delete'));

    }



    protected $fillable=[
        'user_id',
        'level_subject_id',
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
//        'Assignments',//because the unit is not required
        'Lessons',
//        'QuestionBanks',
//        'LearningResources',
//        'LibraryQuestions',
//        'Quizzes',

    ];

    private $mySearchableFields = [
        'name',
        'type',
    ];

    //Attributes




    //Scopes
    public function scopeWithLevelSubjectInfo($query){
        return $query->with(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        }]);
    }

    //Functions
    public function loadLevelSubjectInfo(){
        return $this->load(['LevelSubject'=>function($query){
            return $query->with(['Level','Subject']);
        }]);
    }

}
