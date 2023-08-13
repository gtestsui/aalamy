<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Post\DiscussionCornerPostFileRelations;

class DiscussionCornerPostFile extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use DiscussionCornerPostFileRelations;

    protected $table = 'discussion_corner_post_files';

    public static function customizedBooted(){}


    protected $fillable=[
        'post_id',
        'file',
    ];

    //Attributes
    public function getFileAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        return $key;
    }





    //Scopes

}
