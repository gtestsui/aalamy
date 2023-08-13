<?php


namespace Modules\Level\Http\Controllers\Classes\ManageLevel;


use App\Modules\Level\Http\Controllers\Classes\ManageLevel\BaseLevelAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\User\Models\Educator;

class EducatorLevel extends BaseLevelAbstract
{

    protected Educator $educator;

//    protected $teacherId;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    public function myLevelsQuery()
    {
        $myLevelsQuery = Level::query()->where('user_id',$this->educator->user_id);
        return  $myLevelsQuery;
    }

    public function myLevelSubjectsQuery(){
        $myLevels = $this->myLevels();
        $myLevelIds = $myLevels->pluck('id');

        $myLevelSubjectsQuery = LevelSubject::query();
        $myLevelSubjectsQuery->whereIn('level_id',$myLevelIds)
            ->filterBy($this->filterByFields);
        return $myLevelSubjectsQuery;

    }




}
