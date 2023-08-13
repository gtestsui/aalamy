<?php

namespace Modules\DiscussionCorner\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\DiscussionCorner\Traits\ModelRelations\Post\DiscussionCornerPostPictureRelations;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;

class DiscussionCornerPostPicture extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use DiscussionCornerPostPictureRelations;


    protected $table = 'discussion_corner_post_pictures';

    public static function customizedBooted(){}


    protected $fillable=[
        'post_id',
        'picture',
    ];

    //Attributes
    public function getPictureAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        return $key;
    }




    //Scopes

}
