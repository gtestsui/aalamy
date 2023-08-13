<?php

namespace Modules\FlashCard\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Traits\ModelRelations\FlashCardRelations;

class FlashCard extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use FlashCardRelations;
    protected $table = 'flash_cards';

    public static function customizedBooted(){}


    protected $fillable=[
        'assignment_id',
        'display_time_in_seconds',
        'success_percentage',
        'quiz_time',
        'quiz_time_type',//minutes...
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
        'Cards',
        'MultiChoiceQuestions',
        'TrueFalseQuestions',

    ];

    //Attributes




    //Scopes

}
