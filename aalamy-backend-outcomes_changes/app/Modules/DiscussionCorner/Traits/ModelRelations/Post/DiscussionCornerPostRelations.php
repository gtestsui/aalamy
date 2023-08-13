<?php

namespace Modules\DiscussionCorner\Traits\ModelRelations\Post;



trait DiscussionCornerPostRelations
{

    //Relations
    public function School(){
        return $this->belongsTo('Modules\User\Models\School','school_id');
    }

    public function Educator(){
        return $this->belongsTo('Modules\User\Models\Educator','educator_id');
    }

    public function User(){
        return $this->belongsTo('Modules\User\Models\User','user_id');
    }

    public function AllReplies(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerPostReply','post_id');
    }

    public function Replies(){
//        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerPostReply','post_id')
//            ->limit(config('DiscussionCorner.panel.reply_count_per_page'));
        return $this->AllReplies()->limit(config('DiscussionCorner.panel.reply_count_per_page'));
    }

    public function Pictures(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerPostPicture','post_id');
    }

    public function Videos(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerPostVideo','post_id');
    }


    public function Files(){
        return $this->hasMany('Modules\DiscussionCorner\Models\DiscussionCornerPostFile','post_id');
    }

}
