<?php

namespace Modules\Meeting\Exports;

use App\Http\Controllers\Classes\ApplicationModules;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\Exportable;


use Maatwebsite\Excel\Concerns\FromView;

class MeetingAttendanceExport implements FromView
{
    use Exportable;


    private $meeting;

    public function __construct($meeting){
        $this->meeting = $meeting;

    }

    public function view(): View
    {
        return view(
            ApplicationModules::MEETING_MODULE_NAME.'::export.meetingAttendance', [
            'headerArray' => $this->headings(),
            'meeting' => $this->meeting,
        ]);
    }

    public function headings(): array
    {

        return [
            'student name',
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
