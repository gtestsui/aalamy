<?php


namespace App\Modules\User\Http\Controllers\Classes\ManageStudent;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\ParentModel;

interface ManageStudentParentInterface
{

    public function myStudentIds();
    public function myStudentParentsQuery();
    /**
     * @return ParentModel
     */
    public function myStudentParentsAll();

    /**
     * @return LengthAwarePaginator
     */
    public function myStudentParentsPaginate();


    /**
     * @return ParentModel|Collection
     */
    public function myStudentParentsByClassId($classId);


}
