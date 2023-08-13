<?php

namespace Modules\Mark\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class GradeBookExport implements FromView
{
    use Exportable;


    private $students;
    private $rosterAssignments;
    private $quizzes;
    private $thereAnExternalMarks;

    public function __construct($students,$rosterAssignments,$quizzes,$thereAnExternalMarks){
        $this->students = $students;
        $this->rosterAssignments = $rosterAssignments;
        $this->quizzes = $quizzes;
        $this->thereAnExternalMarks = $thereAnExternalMarks;

    }

    public function view(): View
    {
        return view(
            ApplicationModules::MARK_MODULE_NAME.'::export.gradeBook', [
            'headerArray' => $this->headings(),
            'students' => $this->students,
            'rosterAssignments' => $this->rosterAssignments,
            'quizzes' => $this->quizzes,
            'thereAnExternalMarks' => $this->thereAnExternalMarks,
        ]);
    }

    public function headings(): array
    {
        $array = ['student name','final_grade'];
        foreach ($this->rosterAssignments as $rosterAssignment){
            $rosterAssignmentMark = $this->students[0]['roster_assignments_marks'][$rosterAssignment->id]['roster_assignment_mark'];
            $array [] = $rosterAssignment->Assignment->name.'/'.$rosterAssignmentMark;
        }

        foreach ($this->quizzes as $quiz){
            $array [] = $quiz->name.'/100';
        }
        if($this->thereAnExternalMarks)
            $array [] = 'external_marks';

        return $array;
    }


}
