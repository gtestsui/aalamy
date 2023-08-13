<?php

namespace Modules\Level\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\Filter;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Assignment\Models\Assignment;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\Level\Traits\ModelRelations\LevelSubjectRelations;

class LevelSubject extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use Filter;
    use LevelSubjectRelations;

//    const DELETED='from model';
    protected $table = 'level_subjects';

    public static function customizedBooted(){}


    protected $fillable=[
        'level_id',
        'subject_id',
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
        'ClassInfos',
        'Assignments',
        'Units',
        'QuestionBanks',
        'LearningResources',
        'GradeBooks',
        'LibraryQuestions',
        'Quizzes',

    ];

    private $mySearchableFields = [
    ];

    //Attributes




    //Scopes



}
