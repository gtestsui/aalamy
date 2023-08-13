<?php


namespace App\Modules\User\Http\Controllers\Classes\ManageStudent;


use App\Exceptions\ErrorMsgException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\SchoolStudent;

abstract class BaseManageStudentAbstract
{


    /**
     * @return Builder|EducatorStudent|SchoolStudent|ParentStudent
     */
    public abstract function myStudentsQuery();

    /**
     * @return EducatorStudent|SchoolStudent|Collection from EducatorStudents|SchoolStudents
     */
    public function myStudents(): ?Collection
    {
        $myStudentsQuery = $this->myStudentsQuery();
        $students = $myStudentsQuery->get();
        return  $students;

    }

    /**
     * @return array of studentIds
     */
    public function myStudentIds():array
    {

        $myStudentsQuery = $this->myStudentsQuery();
        $myStudentIds = $myStudentsQuery->pluck('student_id')->toArray();
        return $myStudentIds;

    }

    public function myStudentsWithRelation(): ?Collection
    {
        $myStudentsQuery = $this->myStudentsQuery();
        $myStudents = $myStudentsQuery
            ->with('Student.User')
            ->get();

        return $myStudents;
    }

    public function myStudentsWithRelationPaginate($pageNum=10): ?LengthAwarePaginator
    {
        $myStudentsQuery = $this->myStudentsQuery();
        $myStudents = $myStudentsQuery
            ->with('Student.User')
            ->paginate($pageNum);

        return $myStudents;
    }

    /**
     * @return null|EducatorStudent|SchoolStudent
     */
    public function myStudentByStudentId($studentId)
    {

        $student = $this->myStudentsQuery()
            ->where('student_id',$studentId)
            ->first();
        return  $student;

    }

    /**
     * @return EducatorStudent|SchoolStudent
     */
    public function myStudentByStudentIdOrFail($studentId)
    {
        $student = $this->myStudentByStudentId($studentId);
        if(is_null($student))
            throw new ErrorMsgException('this student doesnt belongs to you');

        return  $student;


    }





}
