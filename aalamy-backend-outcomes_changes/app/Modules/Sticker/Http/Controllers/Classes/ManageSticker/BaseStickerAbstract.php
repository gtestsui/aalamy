<?php


namespace Modules\Sticker\Http\Controllers\Classes\ManageSticker;


use Illuminate\Database\Eloquent\Builder;

abstract class BaseStickerAbstract
{

    /**
     * @return Builder
     */
    abstract protected function getMyStickersQuery();


    public function getAllMyStickers(){
        return $this->getMyStickersQuery()->get();
    }


    public function getMyStickerById($id){
        return $this->getMyStickersQuery()->where('id',$id)->first();
    }

    public function getMyStickerByIdOrFail($id){
        return $this->getMyStickersQuery()->where('id',$id)->firstOrFail();
    }

}
