<?php

namespace Modules\Quiz\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Traits\ModelRelations\QuestionStudentAnswerRelations;
use Modules\Quiz\Traits\ModelRelations\QuizStudentRelations;

class QuizStudent extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use QuizStudentRelations;

    protected $table = 'quiz_students';

    public static function customizedBooted(){}


    protected $fillable=[
        'quiz_id',
        'student_id',
        'start_date',
        'end_date',


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
        'QuizQuestionStudentAnswers',

    ];

    //Attributes



    //Scopes


}
