<?php

namespace Modules\Mark\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class RosterStudentsMarksExport implements FromView
{
    use Exportable;


    private $students;
    private $rosterAssignments;

    public function __construct($students,$rosterAssignments){
        $this->students = $students;
        $this->rosterAssignments = $rosterAssignments;

    }

    public function view(): View
    {
        return view(
            ApplicationModules::MARK_MODULE_NAME.'::export.rosterStudentsMarks', [
            'headerArray' => $this->headings(),
            'students' => $this->students,
            'rosterAssignments' => $this->rosterAssignments,
        ]);
    }

    public function headings(): array
    {
        $array = ['student name'];
        foreach ($this->rosterAssignments as $rosterAssignment){
            $array [] = $rosterAssignment->Assignment->name.' '.$rosterAssignment->start_date;

        }
        return $array;
    }


}
