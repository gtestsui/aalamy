<?php


namespace Modules\Mark\Http\Controllers\Classes\ManageGradeBook;


use App\Exceptions\ErrorUnAuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Modules\Mark\Http\DTO\GradeBookData;
use Modules\Mark\Models\GradeBook;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\User\Models\School;

class SchoolGradeBook extends BaseGradeBookAbstract
{
    private School $school;

    public function __construct(School $school)
    {
        $this->school = $school;

    }

    /**
     * @return Builder
     */
    public function getMyGradeBooksQuery(){
        $myQuery = GradeBook::query()
            ->where('school_id',$this->school->id);

        return $myQuery;

    }

    /**
     * @return  GradeBook
     */
    public function createGradeBook(GradeBookData $gradeBookData){
        return GradeBook::create([
            'school_id' => $this->school->id,
            'grade_book_name' => $gradeBookData->grade_book_name,
            'level_subject_id' => $gradeBookData->level_subject_id,
            'roster_id' => $gradeBookData->roster_id,
            'external_marks_weight' => $gradeBookData->external_marks_weight,
        ]);
    }


}
