<?php

namespace Modules\Quiz\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Traits\ModelRelations\QuizLessonRelations;

class QuizLesson extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use QuizLessonRelations;

    protected $table = 'quiz_lessons';

    public static function customizedBooted(){}


    protected $fillable=[
        'quiz_id',
        'lesson_id',
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

    //Attributes



    //Scopes


}
