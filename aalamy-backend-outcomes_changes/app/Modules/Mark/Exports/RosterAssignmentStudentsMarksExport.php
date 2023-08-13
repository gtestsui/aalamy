<?php

namespace Modules\Mark\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class RosterAssignmentStudentsMarksExport implements FromView
{
    use Exportable;


    private $rosterAssignment;
    private $studentsMarks;

    public function __construct($rosterAssignment,$studentsMarks){
        $this->rosterAssignment = $rosterAssignment;
        $this->studentsMarks = $studentsMarks;

    }

    public function view(): View
    {
        return view(
            ApplicationModules::MARK_MODULE_NAME.'::export.rosterAssignmentStudentsMarks', [
            'rosterAssignment' => $this->rosterAssignment,
            'studentsMarks' => $this->studentsMarks,
            'headerArray' => $this->headings(),
        ]);
    }

    public function headings(): array
    {
        return [
            'student name',
            $this->rosterAssignment->Assignment->name,
        ];
    }


}
