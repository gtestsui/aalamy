<?php


namespace Modules\Mark\Http\Controllers\Classes\ManageGradeBook;


use App\Exceptions\ErrorUnAuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Modules\Mark\Http\DTO\GradeBookData;
use Modules\Mark\Models\GradeBook;
use Modules\Quiz\Models\Quiz;
use Modules\Quiz\Models\QuizStudent;
use Modules\User\Models\Educator;

class EducatorGradeBook extends BaseGradeBookAbstract
{

    private Educator $educator;

    public function __construct(Educator $educator)
    {
        $this->educator = $educator;
    }

    /**
     * @return Builder
     */
    public function getMyGradeBooksQuery(){
        $myQuery = GradeBook::query()
            ->where('educator_id',$this->educator->id);

        return $myQuery;
    }

    /**
     * @return  GradeBook
     */
    public function createGradeBook(GradeBookData $gradeBookData){
       return GradeBook::create([
            'educator_id' => $this->educator->id,
            'grade_book_name' => $gradeBookData->grade_book_name,
            'level_subject_id' => $gradeBookData->level_subject_id,
            'roster_id' => $gradeBookData->roster_id,
            'external_marks_weight' => $gradeBookData->external_marks_weight,
        ]);
    }


}
