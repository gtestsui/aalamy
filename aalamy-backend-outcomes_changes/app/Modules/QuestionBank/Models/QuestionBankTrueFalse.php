<?php

namespace Modules\QuestionBank\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\QuestionBank\Traits\ModelRelations\QuestionBank\TrueFalseRelations;

class QuestionBankTrueFalse extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use TrueFalseRelations;

    protected $table = 'question_bank_true_false';

    public static function customizedBooted(){}


    protected $fillable=[
        'question_id',
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


    ];

    //Attributes



    //Scopes

}
