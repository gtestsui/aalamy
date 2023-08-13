<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Post\DiscussionCornerPostRelations;

class DiscussionCornerPost extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use DiscussionCornerPostRelations;
    protected $table = 'discussion_corner_posts';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'school_id',
        'educator_id',
        'text',
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
//        'AllReplies',//we commented this relations because you can't reach them from anywhere if the post is deleted
//        'Pictures',
//        'Files',

    ];

    protected $mySearchableFields = [
        'text',
        'priority',
    ];

    //Attributes



    //Scopes
    public function scopeApproved($query,$approved=true){
        return $query->where('is_approved',$approved);
    }

    public function scopewithAllRelations($query){
        return $query->with([
            'School','Educator.User','User','Pictures','Files'/*,'Replies'*/,'Videos'
        ]);
    }

    /**
     * return the posts that written by me or return the written by others
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
