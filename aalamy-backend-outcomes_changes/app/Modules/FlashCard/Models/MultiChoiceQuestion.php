<?php

namespace Modules\FlashCard\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Traits\ModelRelations\MultiChoiceQuestionRelations;

class MultiChoiceQuestion extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use MultiChoiceQuestionRelations;
    use SoftDelete;

    protected $table = 'multi_choice_questions';

    public static function customizedBooted(){}


    protected $fillable=[
        'flash_card_id',
        'card_id',
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
        'Choices',

    ];

    //Attributes




    //Scopes

}
