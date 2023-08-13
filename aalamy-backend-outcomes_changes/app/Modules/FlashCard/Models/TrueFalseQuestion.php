<?php

namespace Modules\FlashCard\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Traits\ModelRelations\TrueFalseQuestionRelations;

class TrueFalseQuestion extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use TrueFalseQuestionRelations;
    use SoftDelete;

    protected $table = 'true_false_questions';

    public static function customizedBooted(){}


    protected $fillable=[
        'flash_card_id',
        'question_card_id',
        'answer_card_id',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    //Attributes




    //Scopes

}
