<?php


namespace Modules\ClassModule\Http\Controllers\Classes\ManageClassStudent;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\ClassModule\Http\Controllers\Classes\ClassServices;
use Modules\ClassModule\Http\Controllers\Classes\ManageClass\SchoolClassManagement;
use Modules\ClassModule\Models\ClassModel;
use Modules\ClassModule\Models\ClassStudent;
use Modules\Level\Models\Level;
use Modules\Outcomes\Http\Controllers\Classes\OutcomesServices;
use Modules\Setting\Models\YearSetting;
use Modules\User\Http\Controllers\Classes\ManageStudent\StudentSchoolClass;
use Modules\User\Models\School;

class SchoolClassStudent implements ManageClassStudentInterface
{

    private School $school;
    public function __construct(School $school)
    {
        $this->school = $school;
    }


    public function addStudentToClassOrMoveToAnotherClass($classId,$studentId){
        $studentSchoolClass = new StudentSchoolClass($this->school);
        $schoolStudent = $studentSchoolClass->myStudentByStudentIdOrFail($studentId);
        $foundClassStudent = ClassStudent::where('student_id',$studentId)
            ->where('school_id',$this->school->id)
            ->active()
            ->get();

        ClassServices::checkStudentAlreadyBelongsToClass($classId,$studentId);

        foreach ($foundClassStudent as $classStudent){
            $classStudent->activate(false);
        }
    	//TODO : here we should call OutcomesServices::initialize() like $this->addMoreThanStudent()
        $yearSetting = YearSetting::first();
        $classModle = ClassModel::with('Level')->findOrFail($classId);
        OutcomesServices::initialize(
            $studentId,
            $this->school->id,
            $classModle->Level,
            $yearSetting
        );
        ClassStudent::create([
            'class_id' => $classId,
            'student_id' => $studentId,
            'teacher_id' => null,
            'school_id' => $this->school->id,
            'study_year' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }


    public function addMoreThanStudent($classId,array $requestStudentIds){
        $studentSchoolClass = new StudentSchoolClass($this->school);
        $myStudents = $studentSchoolClass->myStudents();
        $myStudentIds = $myStudents->pluck('student_id')->toArray();
        //get the shared ids between my student and the student in request
        $studentIds = array_intersect($myStudentIds,$requestStudentIds);

        //here check if the student belongs to another class in the same school
        $foundClassStudent = ClassStudent::whereIn('student_id',$studentIds)
            ->where('school_id',$this->school->id)
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
                'teacher_id' => null,
                'school_id' => $this->school->id,
                'study_year' => $yearSetting->start_date,
                'created_at' => Carbon::now()
            ];

            OutcomesServices::initialize(
                $studentId,
                $this->school->id,
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
        $manageClass = new SchoolClassManagement($this->school);
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
        //I don't remember why im checking on class authorization here
        $manageClass = new SchoolClassManagement($this->school);
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
