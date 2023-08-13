<?php

namespace Modules\Quiz\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Traits\ModelRelations\QuizQuestionRelations;
use Modules\Quiz\Traits\ModelRelations\QuizRelations;
use Modules\Sticker\Traits\ModelRelations\StickerRelations;

class QuizQuestion extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use QuizQuestionRelations;

    protected $table = 'quiz_questions';

    public static function customizedBooted(){}


    protected $fillable=[
        'quiz_id',
        'question_id',
        'mark',


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
        'QuizQuestionStudentAnswers'
    ];

    //Attributes



    //Scopes


}
