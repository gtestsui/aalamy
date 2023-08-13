<?php


namespace Modules\Level\Http\Controllers\Classes\ManageUnit;


use App\Modules\Level\Http\Controllers\Classes\ManageUnit\BaseUnitAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Level\Models\Unit;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionServices;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;

class TeacherUnit extends BaseUnitAbstract
{

    protected Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }


    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * when the unitId is not null that mean get myUnit by id or null
     */
    public function myUnitsQuery($unitId=null): Builder
    {
        $havePermission = PermissionServices::isHaveOneOfThisPermissions($this->teacher,[
           'unit' => ['create','update','delete'],
           'lesson' => ['create','update','delete'],
        ]);
        if($havePermission){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolUnitClass = new SchoolUnit($school);
            return $schoolUnitClass->myUnitsQuery();
        }else{
            $myUnitsQuery = Unit::query();
            $myUnitsQuery->where('user_id',$this->teacher->user_id)
                ->byNullableId($unitId)
                ->where('type','teacher');
            return $myUnitsQuery;
        }

    }





}
