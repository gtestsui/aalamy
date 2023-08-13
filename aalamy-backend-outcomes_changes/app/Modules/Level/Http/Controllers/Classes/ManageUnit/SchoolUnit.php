<?php


namespace Modules\Level\Http\Controllers\Classes\ManageUnit;


use App\Modules\Level\Http\Controllers\Classes\ManageUnit\BaseUnitAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Level\Models\Unit;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class SchoolUnit extends BaseUnitAbstract
{

    protected School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * when the unitId is not null that mean get myUnit by id or null
     */
    public function myUnitsQuery($unitId=null): Builder
    {
        $teacherAndMySchoolUserIds = Teacher::where('school_id',$this->school->id)
            ->pluck('user_id');

        $teacherAndMySchoolUserIds[] = $this->school->user_id;
        $myUnitsQuery = Unit::query();
        $myUnitsQuery->whereIn('user_id',$teacherAndMySchoolUserIds)
            ->byNullableId($unitId)
            ->where(function ($query){
                return $query->where('type','school')
                    ->orWhere('type','teacher');
            });
        return $myUnitsQuery;

    }



}
