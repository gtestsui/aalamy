<?php


namespace Modules\User\Http\Controllers\Classes\ManageStudent;


use App\Modules\User\Http\Controllers\Classes\ManageStudent\BaseManageStudentAbstract;
use App\Modules\User\Http\Controllers\Classes\ManageStudent\ManageStudentParentInterface;
use Illuminate\Database\Eloquent\Builder;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\TeacherClassManagement;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Http\Controllers\Classes\ManageUnit\SchoolUnit;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionServices;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Teacher;
use Modules\User\Traits\ManageStudentParentTrait;

class StudentTeacherClass extends BaseManageStudentAbstract implements ManageStudentParentInterface
{

    use ManageStudentParentTrait;


    protected Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    public function myStudentsQuery(): ?Builder
    {

        $havePermission = PermissionServices::isHaveOneOfThisPermissions($this->teacher,[
            'student' => ['create','delete'],
        ]);
        if($havePermission){
            $school = School::findOrFail($this->teacher->school_id);
            $schoolStudentClass = new StudentSchoolClass($school);
            return $schoolStudentClass->myStudentsQuery();
        }else{
            $manageClass = new TeacherClassManagement($this->teacher);
            $myClasses = $manageClass->myClasses();

            $myClassIds = $myClasses->pluck('id');
            $classStudentsQuery = ClassStudent::query();
            $classStudentsQuery->whereIn('class_id',$myClassIds);
            return  $classStudentsQuery;

        }

    }

//    public function myStudents(): ?Collection
//    {
//        $classStudentsQuery = $this->myStudentsQuery();
//        $classStudents = $classStudentsQuery->get();
//
//        return  $classStudents;
//    }
//
//    /**
//     * @return array of studentIds
//     */
//    public function myStudentIds():array
//    {
//
//        $myStudentsQuery = $this->myStudentsQuery();
//        $myStudentIds = $myStudentsQuery->pluck('student_id')->toArray();
//        return $myStudentIds;
//
//    }
//
//    public function myStudentsWithRelation(): ?Collection
//    {
//
//        $classStudentsQuery = $this->myStudentsQuery();
//        $classStudents = $classStudentsQuery
//            ->with('Student.User')
//            ->get();
//
//        return  $classStudents;
//    }
//
//    public function myStudentsWithRelationPaginate($pageNum=10): ?LengthAwarePaginator
//    {
//        $myStudentsQuery = $this->myStudentsQuery();
//        $myStudents = $myStudentsQuery
//            ->with('Student.User')
//            ->paginate($pageNum);
//
//        return $myStudents;
//    }
//
//    public function myStudentByStudentId($studentId)
//    {
//        $manageClass = new TeacherClassManagement($this->teacher);
//        $myClasses = $manageClass->myClasses();
//
//        $myClassIds = $myClasses->pluck('id');
//
//        $classStudent = ClassStudent::whereIn('id',$myClassIds)
//            ->where('student_id',$studentId)
//            ->first();
//        return $classStudent;
//    }
//
//    public function myStudentByStudentIdOrFail($studentId):ClassStudent
//    {
//        $classStudent = $this->myStudentByStudentId($studentId);
//        if(is_null($classStudent))
//            throw new ErrorMsgException('this student doesnt belongs to you');
//
//        return $classStudent;
//    }


//    /**
//     * @return ParentModel
//     */
//    public function myStudentParentsQuery(){
////        $myStudents = $this->myStudents();
////        $myStudentIds = $myStudents->pluck('student_id');
//        $myStudentIds = $this->myStudentIds();
//
//        $myStudentParentsQuery = ParentModel::query()
//            ->hasStudent($myStudentIds)
//            ->active();
//        return $myStudentParentsQuery;
//    }
//
//    /**
//     * @return ParentModel
//     */
//    public function myStudentParentsAll(){
//        $myStudentParents = $this->myStudentParentsQuery()
//            ->get();
//        return $myStudentParents;
//    }
//
//    /**
//     * @return ParentModel
//     */
//    public function myStudentParentsPaginate(){
//        $myStudentParents = $this->myStudentParentsQuery()
//            ->paginate(10);
//        return $myStudentParents;
//    }




}
