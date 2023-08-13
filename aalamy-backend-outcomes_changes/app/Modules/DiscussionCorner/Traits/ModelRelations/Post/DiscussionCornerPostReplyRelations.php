<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Post;


use Modules\ClassModule\Models\ClassStudent;
use Modules\Roster\Models\Roster;

trait DiscussionCornerPostReplyRelations
{

    //Relations
    public function Post(){
        return $this->belongsTo('Modules\DiscussionCorner\Models\DiscussionCornerPost','post_id');
    }

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

}
