<?php

namespace Modules\RosterAssignment\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class StudentAttendanceExport implements FromView
{
    use Exportable;


    private $rosterAssignmentStudentsAttendances;
    public function __construct($rosterAssignmentStudentsAttendances){
        $this->rosterAssignmentStudentsAttendances = $rosterAssignmentStudentsAttendances;
    }

    public function view(): View
    {
        return view(
            ApplicationModules::ROSTER_ASSIGNMENT_MODULE_NAME.'::export.rosterAssignmentAttendance', [
            'rosterAssignmentStudentsAttendances' => $this->rosterAssignmentStudentsAttendances,
            'headerArray' => $this->headings(),
        ]);
    }

    public function headings(): array
    {
        return [
            'student name',
            'attendee status',
            'group name',
            'assignment name',
            'assignment date',
        ];
    }


}
