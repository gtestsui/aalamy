<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Survey\DiscussionCornerSurveyUserAnswerRelations;

class DiscussionCornerSurveyUserAnswer extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use DiscussionCornerSurveyUserAnswerRelations;

    protected $table = 'discussion_corner_survey_user_answers';

    public static function customizedBooted(){}


    protected $fillable=[
        'survey_user_id',
        'question_id',
        'choice_id',
        'written_answer',

        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    //Attributes




    //Scopes
    public function scopewithAllRelations($query){
        return $query->with(['SurveyUsers','Question','Choice']);
    }

}
