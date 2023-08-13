<?php


namespace App\Modules\Level\Http\Controllers\Classes\ManageUnit;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Level\Models\Unit;

interface ManageUnitInterface
{


    public function myUnitsPaginate(): LengthAwarePaginator;
//    public function myUnitsByLevelSubjectOrPaginate($levelSubjectId=null): LengthAwarePaginator;
    public function myUnitsPaginateWithFilter($levelSubjectId=null): LengthAwarePaginator;

    /**
     * @return Collection of Unit model
     */
    public function myUnitsAll(): Collection;

    /**
     * @return Unit|null|Builder of Unit model
     */
    public function myUnitsById(?int $unitId): Unit;

}
