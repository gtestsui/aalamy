<?php


namespace Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\TeacherClassManagement;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Models\Level;
use Modules\Outcomes\Http\Controllers\Classes\OutcomesServices;
use Modules\Setting\Models\YearSetting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentTeacherClass;
use Modules\User\Models\Teacher;

class TeacherClassStudent implements ManageClassStudentInterface
{

    private Teacher $teacher;
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /** this is incorrect code (the teacher can add students to class?
     *  if yes -> we should get the school students and the get the shared with requested student
     * if no ->  this class should delete this function
     */
    public function addMoreThanStudent($classId,array $requestStudentIds){
        $studentTeacherClass = new StudentTeacherClass($this->teacher);
        $myStudents = $studentTeacherClass->myStudents();
        $myStudentIds = $myStudents->pluck('student_id')->toArray();
        //get the shared ids between my student and the student in request
        $studentIds = array_intersect($myStudentIds,$requestStudentIds);

        //here check if the student belongs to another class in the same school
        $foundClassStudent = ClassStudent::whereIn('student_id',$studentIds)
            ->where('teacher_id',$this->teacher->id)
            ->active()
            ->get();
        if(count($foundClassStudent))
            throw new ErrorMsgException(transMsg('student_existed_in_class_before',ApplicationModules::CLASS_MODULE_NAME));


        $class = ClassModel::findOrFail($classId);
        $level = Level::findOrFail($class->level_id);
        $yearSetting = YearSetting::first();

        $arrayForCreate = [];
        foreach ($studentIds as $studentId){
            $arrayForCreate[] = [
                'class_id' => $classId,
                'student_id' => $studentId,
                'teacher_id' => $this->teacher->id,
                'school_id' => $this->teacher->school_id,
                'study_year' => Carbon::now(),
                'created_at' => Carbon::now()
            ];

            OutcomesServices::initialize(
                $studentId,
                $this->teacher->school_id,
                $level,
                $yearSetting
            );

        }
        ClassStudent::insert($arrayForCreate);

    }


    /**
     * @return Builder
     */
    public function myClassStudentsQuery(){
        $manageClass = new TeacherClassManagement($this->teacher);
        $myClasses = $manageClass->myClasses();
        $myClassIds = $myClasses->pluck('id');
        $myClassStudentsQuery = ClassStudent::query()
            ->whereIn('class_id',$myClassIds);
        return $myClassStudentsQuery;
    }

    /**
     * @return Builder
     */
    public function myClassStudentsByClassIdQuery($classId){
        $manageClass = new TeacherClassManagement($this->teacher);
        $myClass = $manageClass->myClassesByIdOrFail($classId);

        $myClassStudentsQuery = ClassStudent::query()
            ->where('class_id',$classId);
        return $myClassStudentsQuery;
    }

    /**
     * @return Collection|ClassStudent|Builder
     */
    public function myClassStudents(){
        return $this->myClassStudentsQuery()->get();
    }

    /**
     * we used get() because the student present in more than class
     * if him belong to one class we can use first() instead
     */
    public function myClassStudentByStudentId($studentId){
        return $this->myClassStudentsQuery()
            ->where('student_id',$studentId)
            ->get();
    }

    /**
     * @return Collection|ClassStudent|Builder
     */
    public function myClassStudentsByClassId($classId){
        return $this->myClassStudentsByClassIdQuery($classId)->get();
    }

    /**
     * @return Collection|ClassStudent|Builder
     */
    public function myClassStudentsByClassIdAndStudentIds($classId,$studentIds){
        return $this->myClassStudentsByClassIdQuery($classId)
            ->whereIn('student_id',$studentIds)
            ->get();
    }

}
