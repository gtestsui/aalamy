<?php


namespace Modules\Sticker\Http\Controllers\Classes\ManageSticker;


use Illuminate\Database\Eloquent\Builder;
use Modules\Sticker\Models\Sticker;
use Modules\User\Models\School;

class SchoolSticker extends BaseStickerAbstract
{
    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;

    }

    /**
     * @return Builder
     */
    public function getMyStickersQuery(){
        $myStickersQuery = Sticker::query()
            ->where('school_id',$this->school->id)
            ->orWhere(function ($query){
                return $query->whereNull('educator_id')
                    ->whereNull('school_id')
                    ->whereNull('teacher_id');
            });

        return $myStickersQuery;

    }


}
