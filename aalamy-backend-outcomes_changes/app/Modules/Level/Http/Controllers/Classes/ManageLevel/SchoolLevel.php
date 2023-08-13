<?php


namespace Modules\Level\Http\Controllers\Classes\ManageLevel;


use App\Modules\Level\Http\Controllers\Classes\ManageLevel\BaseLevelAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Level\Models\Level;
use Modules\Level\Models\LevelSubject;
use Modules\User\Models\School;

class SchoolLevel extends BaseLevelAbstract
{

    protected School $school;

    public function __construct(School $school/*,$teacherId=null*/)
    {
        $this->school = $school;
    }

    /**
     * @return Builder
     */
    public function myLevelsQuery()
    {
        $levels = Level::query()->where('user_id',$this->school->user_id);
        return  $levels;
    }


    /**
     * @return Builder
     */
    public function myLevelSubjectsQuery(){
        $myLevels = $this->myLevels();
        $myLevelIds = $myLevels->pluck('id');

        $myLevelSubjectsQuery = LevelSubject::query();
        $myLevelSubjectsQuery->whereIn('level_id',$myLevelIds)
            ->filterBy($this->filterByFields);
        return $myLevelSubjectsQuery;

    }


}
