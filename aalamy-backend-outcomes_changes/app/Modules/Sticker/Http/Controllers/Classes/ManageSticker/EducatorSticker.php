<?php


namespace Modules\Sticker\Http\Controllers\Classes\ManageSticker;


use Illuminate\Database\Eloquent\Builder;
use Modules\Sticker\Models\Sticker;
use Modules\User\Models\Educator;

class EducatorSticker extends BaseStickerAbstract
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    public function getMyStickersQuery(){
        $myStickersQuery = Sticker::query()
            ->where('educator_id',$this->educator->id)
            ->orWhere(function ($query){
                return $query->whereNull('educator_id')
                    ->whereNull('school_id')
                    ->whereNull('teacher_id');
            });

        return $myStickersQuery;
    }


}
