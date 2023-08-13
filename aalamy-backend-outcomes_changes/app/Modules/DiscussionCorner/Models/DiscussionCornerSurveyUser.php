<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Survey\DiscussionCornerSurveyUserRelations;

class DiscussionCornerSurveyUser extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use DiscussionCornerSurveyUserRelations;

    protected $table = 'discussion_corner_survey_users';

    public static function customizedBooted(){}


    protected $fillable=[
        'survey_id',
        'user_id',
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
        'UserAnswers',

    ];

    //Attributes





    //Scopes
    public function scopewithAllRelations($query){
        return $query->with(['Survey','User']);
    }

}
