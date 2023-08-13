<?php

namespace Modules\Sticker\Traits\ModelRelations;


use Modules\Sticker\Models\StudentPageSticker;
use Modules\User\Models\Educator;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

trait StickerRelations
{

    //Relations
    public function School(){
        return $this->belongsTo(School::class,'school_id');
    }

    public function Educator(){
        return $this->belongsTo(Educator::class,'educator_id');
    }

    public function Teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function StudentPageStickers(){
        return $this->hasMany(StudentPageSticker::class,'sticker_id');
    }


}
