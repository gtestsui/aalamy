<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Post\DiscussionCornerPostPictureRelations;
use Modules\DiscussionCorner\Traits\ModelRelations\Post\DiscussionCornerPostVideoRelations;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;

class DiscussionCornerPostVideo extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use DiscussionCornerPostVideoRelations;


    protected $table = 'discussion_corner_post_videos';

    public static function customizedBooted(){}


    protected $fillable=[
        'post_id',
        'video',
    ];

    //Attributes
    public function getVideoAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        return $key;
    }




    //Scopes

}
