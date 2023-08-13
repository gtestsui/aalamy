<?php

namespace Modules\Meeting\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class StudentMeetingsAttendanceExport implements FromView
{
    use Exportable;


    private $meetings;

    public function __construct($meetings){
        $this->meetings = $meetings;

    }

    public function view(): View
    {
        return view(
            ApplicationModules::MEETING_MODULE_NAME.'::export.studentMeetingAttendance', [
            'headerArray' => $this->headings(),
            'meetings' => $this->meetings,
        ]);
    }

    public function headings(): array
    {

        return [
            'meeting name',
            'attendee status',
        ];

//        $array = ['student name'];
//        foreach ($this->rosterAssignments as $rosterAssignment){
//            $array [] = $rosterAssignment->Assignment->name.' '.$rosterAssignment->start_date;
//
//        }
//        return $array;
    }


}
