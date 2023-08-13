<?php

namespace Modules\FlashCard\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\FlashCard\Traits\ModelRelations\FlashCardQuizStudentPercentageRelations;

class FlashCardQuizStudentPercentage extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use FlashCardQuizStudentPercentageRelations;
    protected $table = 'flash_card_quiz_student_percentages';

    public static function customizedBooted(){}


    protected $fillable=[
        'flash_card_id',
        'student_id',
        'percentage',

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
