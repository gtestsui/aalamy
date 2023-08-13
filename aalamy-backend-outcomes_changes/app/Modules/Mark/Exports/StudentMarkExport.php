<?php

namespace Modules\Mark\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class StudentMarkExport implements FromView
{
    use Exportable;


    private $student;
    private $rosterAssignmentsWithMarks;
    public function __construct($student,$rosterAssignmentsWithMarks){
        $this->student = $student;
        $this->rosterAssignmentsWithMarks = $rosterAssignmentsWithMarks;
    }

    public function view(): View
    {
        return view(
            ApplicationModules::MARK_MODULE_NAME.'::export.studentMarks', [
            'rosterAssignmentsWithMarks' => $this->rosterAssignmentsWithMarks,
            'student' => $this->student,
            'headerArray' => $this->headings(),
        ]);
    }

    public function headings(): array
    {
        $array = ['student name'];
        foreach ($this->rosterAssignmentsWithMarks as $rosterAssignemntMark){
            $array [] = $rosterAssignemntMark->Assignment->name.'['.$rosterAssignemntMark->roster_assignment_original_mark.']';
        }
        return $array;
//        return [
//            'student name',
//            'attendee status',
//            'roster name',
//            'assignment name',
//            'assignment date',
//        ];
    }


}
