<?php


namespace Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;

interface ManageClassStudentInterface
{


    /**
     * get shared student between my student and the student in request then store the shared
     */
    public function addMoreThanStudent($classId,array $requestStudentIds);


    /**
     * get all ClassStudent belongs to my classes
     * @return Collection|ClassStudent|Builder
     */
    public function myClassStudents();


    /**
     * get all ClassStudent belongs to my classes depends on student id
     * @return Collection of ClassStudent
     */
    public function myClassStudentByStudentId($studentId);



    /**
     * @return Builder
     */
    public function myClassStudentsQuery();


    /**
     * @return Builder
     */
    public function myClassStudentsByClassIdQuery($classId);



    /**
     * @return Collection|ClassStudent|Builder
     */
    public function myClassStudentsByClassId($classId);

    /**
     * @return Collection|ClassStudent|Builder
     */
    public function myClassStudentsByClassIdAndStudentIds($classId,$studentIds);


}
