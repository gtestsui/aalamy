<?php

namespace Modules\Quiz\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Traits\ModelRelations\QuestionStudentAnswerRelations;

class QuizQuestionFillInBlankAnswer extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
//    use QuestionStudentAnswerRelations;

    protected $table = 'quiz_question_fill_in_blank_answers';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByOrderingPriority', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('id', 'asc');

        });
    }


    protected $fillable=[
        'quiz_question_student_answer_id',
        'word',
        'order',
        'answer_status',


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
