<?php


namespace Modules\RosterAssignment\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class FilterRosterAssignmentAttendanceData extends ObjectData
{
    public ?int       $id=null;
    public ?int       $student_id;

    public FilterRosterAssignmentData $filter_roster_assignment_data;
//    public ?array       $roster_assignment_ids;
//    public ?int       $roster_assignment_id;
//
//    public ?Carbon     $start_date;
//    public ?Carbon     $end_date;


    public static function fromRequest(Request $request): self
    {

        return new self([
            'student_id' => isset($request->student_id)?(int)$request->student_id :null,
            'filter_roster_assignment_data' => FilterRosterAssignmentData::fromRequest($request),

//            'roster_assignment_ids' => isset($request->roster_assignment_ids)?$request->roster_assignment_ids :[],
////            'roster_assignment_id' => isset($request->roster_assignment_id)?(int)$request->roster_assignment_id :null,
//
//
//            'start_date' => Self::generateCarbonObject($request->start_date) ,
//            'end_date' => Self::generateCarbonObject($request->end_date),
        ]);
    }


    public static function fromArray(array $array){
        return new self([
            'student_id' => isset($request->student_id)?(int)$request->student_id :null,
            'filter_roster_assignment_data' => FilterRosterAssignmentData::fromArray($array),

        ]);
    }




}
