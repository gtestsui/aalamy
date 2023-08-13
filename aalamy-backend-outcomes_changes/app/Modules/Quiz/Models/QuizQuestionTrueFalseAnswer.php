<?php

namespace Modules\Quiz\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Traits\ModelRelations\QuestionStudentAnswerRelations;

class QuizQuestionTrueFalseAnswer extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
//    use QuestionStudentAnswerRelations;

    protected $table = 'quiz_question_true_false_answers';

    public static function customizedBooted(){}


    protected $fillable=[
        'quiz_question_student_answer_id',
        'chosen_status',


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

    //Attributes



    //Scopes


}
