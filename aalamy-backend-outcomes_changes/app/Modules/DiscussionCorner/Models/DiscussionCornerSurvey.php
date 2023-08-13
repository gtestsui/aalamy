<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Survey\DiscussionCornerSurveyRelations;

class DiscussionCornerSurvey extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use DiscussionCornerSurveyRelations;

    protected $table = 'discussion_corner_surveys';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'school_id',
        'educator_id',
        'subject',
        'description',
        'priority',
        'is_approved',
        'user_type',
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
        'SurveyQuestions',//we commented these relations because you can't reach them from anywhere if the post is deleted
//        'SurveyUser',
          'SurveyUsers'

    ];

    protected $mySearchableFields = [
        'subject',
        'description',
        'priority',
    ];

    //Attributes







    //Scopes
    public function scopeApproved($query,$approved=true){
        return $query->where('is_approved',$approved);
    }

    public function scopewithAllRelations($query){
        return $query->with(['School','Educator','User']);
    }

    public function scopeAnsweredFromMe($query,$userId,$status=true){
        if(!$status) {
            return $query->whereDoesntHave('SurveyUsers', function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            });
        }
    }

    /**
     * return the surveys that written by me or return the written by others
     * depends on $imTheWriter value
     */
    public function scopeImTheWriter($query,$userId,bool $imTheWriter=true){
        if($imTheWriter)
            return $query->where('user_id',$userId);
        else
            return $query->where('user_id','!=',$userId);

    }



    //Functions
    public function approve(){
        $this->update([
            'is_approved' => 1
        ]);
    }

}
