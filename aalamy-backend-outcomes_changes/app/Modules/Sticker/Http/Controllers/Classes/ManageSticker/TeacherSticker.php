<?php


namespace Modules\Sticker\Http\Controllers\Classes\ManageSticker;


use Illuminate\Database\Eloquent\Builder;
use Modules\Sticker\Models\Sticker;
use Modules\User\Models\Teacher;

class TeacherSticker extends BaseStickerAbstract
{
    private Teacher $teacher;

    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;

    }

    /**
     * @return Builder
     */
    public function getMyStickersQuery(){
        $myStickersQuery = Sticker::query()
            ->where('teacher_id',$this->teacher->id)
            ->orWhere(function ($query){
                return $query->whereNull('educator_id')
                    ->whereNull('school_id')
                    ->whereNull('teacher_id');
            });
        return $myStickersQuery;
    }

}
