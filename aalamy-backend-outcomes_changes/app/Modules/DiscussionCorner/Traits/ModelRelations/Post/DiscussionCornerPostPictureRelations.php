<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Post;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;

trait DiscussionCornerPostPictureRelations
{

    //Relations
    public function Post(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerPost','post_id');
    }


}
