<?php

namespace Modules\Sticker\Traits\ModelRelations;


use Modules\Sticker\Models\Sticker;

trait StudentPageStickerRelations
{

    //Relations
    public function Sticker(){
        return $this->belongsTo(Sticker::class,'sticker_id');
    }


}
