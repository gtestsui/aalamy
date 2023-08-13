<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Post\DiscussionCornerPostReplyRelations;

class DiscussionCornerPostReply extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use DiscussionCornerPostReplyRelations;

    protected $table = 'discussion_corner_post_replies';

    public static function customizedBooted(){}


    protected $fillable=[
        'post_id',
        'user_id',
        'picture',
        'text',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    protected $mySearchableFields = [
        'text',
    ];

    //Attributes
    public function getPictureAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        return $key;
    }





    //Scopes

}
