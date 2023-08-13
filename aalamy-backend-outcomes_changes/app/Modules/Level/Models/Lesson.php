<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Assignment\Models\Assignment;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\Level\Traits\ModelRelations\LessonRelations;

class Lesson extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use ModelSharedScopes;
    use LessonRelations;
    protected $table = 'lessons';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'unit_id',
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
//        'Assignments',//because the lesson is not required
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

}
