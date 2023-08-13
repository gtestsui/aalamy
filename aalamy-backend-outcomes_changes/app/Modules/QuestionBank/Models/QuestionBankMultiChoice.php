<?php

namespace Modules\QuestionBank\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionBank\Traits\ModelRelations\QuestionBank\MultiChoiceRelations;

class QuestionBankMultiChoice extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use MultiChoiceRelations;

    protected $table = 'question_bank_multi_choices';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByIdAsc', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('id', 'asc');

        });
    }


    protected $fillable=[
        'question_id',
        'choice',
        'status',
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

        'QuizQuestionMultiChoiceAnswers',

    ];

    //Attributes



    //Scopes

}
