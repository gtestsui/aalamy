<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Survey\DiscussionCornerSurveyQuestionChoiceRelations;

class DiscussionCornerSurveyQuestionChoice extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use DiscussionCornerSurveyQuestionChoiceRelations;

    protected $table = 'discussion_corner_survey_question_choices';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('normalOrderBy', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('id', 'asc');

        });

    }


    protected $fillable=[
        'question_id',
        'choice',
        'counter',

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
        'SurveyUserAnswers',

    ];

    //Attributes




    //Scopes
    public function scopewithAllRelations($query){
        return $query->with(['Question']);
    }

}
