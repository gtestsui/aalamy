<?php

namespace Modules\RosterAssignment\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class RosterStudentsAttendanceExport implements FromView
{
    use Exportable;


    private $rosterAssignmentStudentsAttendances;
    private $rosterStudents;
    private $rosterAssignments;

    public function __construct($rosterAssignmentStudentsAttendances,$rosterStudents,$rosterAssignments){
        $this->rosterAssignmentStudentsAttendances = $rosterAssignmentStudentsAttendances;
        $this->rosterStudents = $rosterStudents;
        $this->rosterAssignments = $rosterAssignments;

    }

    public function view(): View
    {
        return view(
            ApplicationModules::ROSTER_ASSIGNMENT_MODULE_NAME.'::export.rosterStudentsAttendance', [
            'rosterAssignmentStudentsAttendances' => $this->rosterAssignmentStudentsAttendances,
            'headerArray' => $this->headings(),
            'rosterAssignments' => $this->rosterAssignments,
            'rosterStudents' => $this->rosterStudents,
        ]);
    }

    public function headings(): array
    {
        $array = ['student name'];
        foreach ($this->rosterAssignments as $rosterAssignment){
            $array [] = $rosterAssignment->Assignment->name.' '.$rosterAssignment->start_date;

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
