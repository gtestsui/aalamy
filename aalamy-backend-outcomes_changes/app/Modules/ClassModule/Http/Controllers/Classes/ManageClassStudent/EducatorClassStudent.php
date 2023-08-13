<?php


namespace Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\EducatorClassManagement;
use Modules\ClassModule\Models\ClassStudent;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentEducatorClass;
use Modules\User\Models\Educator;

class EducatorClassStudent implements ManageClassStudentInterface
{

    private Educator $educator;
    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    public function addStudentToClassOrMoveToAnotherClass($classId,$studentId){
        $studentEducatorClass = new StudentEducatorClass($this->educator);
        $myStudent = $studentEducatorClass->myStudentByStudentIdOrFail($studentId);
        $foundClassStudent = ClassStudent::where('student_id',$studentId)
            ->where('educator_id',$this->educator->id)
            ->active()
            ->get();

        ClassServices::checkStudentAlreadyBelongsToClass($classId,$studentId);

        foreach ($foundClassStudent as $classStudent){
            $classStudent->activate(false);
        }
        ClassStudent::create([
            'class_id' => $classId,
            'student_id' => $studentId,
            'educator_id' => $this->educator->id,
            'study_year' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }



    public function addMoreThanStudent($classId,array $requestStudentIds){
        $studentEducatorClass = new StudentEducatorClass($this->educator);
        $myStudents = $studentEducatorClass->myStudents();
        $myStudentIds = $myStudents->pluck('student_id')->toArray();
        //get the shared ids between my student and the student in request
        $studentIds = array_intersect($myStudentIds,$requestStudentIds);

        //here check if the student belongs to another class in the same educator
        $foundClassStudent = ClassStudent::whereIn('student_id',$studentIds)
            ->where('educator_id',$this->educator->id)
            ->active()
            ->get();
        if(count($foundClassStudent))
            throw new ErrorMsgException(transMsg('student_existed_in_class_before',ApplicationModules::CLASS_MODULE_NAME));


        $arrayForCreate = [];
        foreach ($studentIds as $studentId){
            $arrayForCreate[] = [
                'class_id' => $classId,
                'student_id' => $studentId,
                'educator_id' => $this->educator->id,
                'study_year' => Carbon::now(),
                'created_at' => Carbon::now()
            ];
        }
        ClassStudent::insert($arrayForCreate);

    }


    /**
     * @return Builder
     */
    public function myClassStudentsQuery(){
        $manageClass = new EducatorClassManagement($this->educator);
        $myClasses = $manageClass->myClasses();
        $myClassIds = $myClasses->pluck('id');
        $myClassStudentsQuery = ClassStudent::query()
            ->whereIn('class_id',$myClassIds)
            ->active();
        return $myClassStudentsQuery;
    }

    /**
     * @return Builder
     */
    public function myClassStudentsByClassIdQuery($classId){
        $manageClass = new EducatorClassManagement($this->educator);
        $myClass = $manageClass->myClassesByIdOrFail($classId);

        $myClassStudentsQuery = ClassStudent::query()
            ->where('class_id',$classId)
            ->active();
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
