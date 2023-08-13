<?php


namespace Modules\Level\Http\Controllers\Classes\ManageUnit;


use App\Modules\Level\Http\Controllers\Classes\ManageUnit\BaseUnitAbstract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Level\Models\Unit;
use Modules\User\Models\Educator;

class EducatorUnit extends BaseUnitAbstract
{

    protected Educator $educator;
    protected $teacherId;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }


    /**
     * {@inheritdoc}
     */
    public function myUnitsQuery($unitId=null):Builder
    {
        $unitsQuery = Unit::query();
        $unitsQuery->where('user_id',$this->educator->user_id)
            ->byNullableId($unitId)
            ->where('type','educator');
        return $unitsQuery;
    }




}
