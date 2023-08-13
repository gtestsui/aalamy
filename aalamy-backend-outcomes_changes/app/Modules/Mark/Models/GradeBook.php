<?php

namespace Modules\Mark\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Mark\Traits\ModelRelations\GradeBookRelations;


class GradeBook extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use GradeBookRelations;

    protected $collection  = 'grade_books';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'teacher_id',
        'educator_id',
        'roster_id',
        'level_subject_id',
        'grade_book_name',
        'external_marks_weight',
        'file',

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
        'GradeBookQuizzes',
        'GradeBookRosterAssignments',
        'GradeBookExternalMarks',
    ];

    //Attributes
    public function getFileAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        else
            return null;
    }



    //Scopes


}
