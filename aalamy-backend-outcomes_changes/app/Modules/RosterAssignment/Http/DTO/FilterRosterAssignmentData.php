<?php


namespace Modules\RosterAssignment\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Carbon\Carbon;

final class FilterRosterAssignmentData extends ObjectData
{
    public ?int         $id=null;
//    public ?int       $roster_id;
//    public ?int       $student_id;
    public ?array       $roster_assignment_ids;
    public array        $roster_ids;
//    public ?int       $roster_assignment_id;

    public ?int       $level_subject_id;
    public ?int       $unit_id;
    public ?int       $lesson_id;

    public ?Carbon     $start_date;
    public ?Carbon     $end_date;

////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request): self
    {

        return new self([
//            'roster_assignment_ids' => isset($request->roster_assignment_ids)?$request->roster_assignment_ids :[],
            'roster_assignment_ids' => isset($request->roster_assignment_ids)?$request->roster_assignment_ids :null,
            'roster_ids' => isset($request->roster_ids)?$request->roster_ids :[],

            'level_subject_id' => isset($request->level_subject_id)?(int)$request->level_subject_id:$request->level_subject_id ,
            'unit_id' => isset($request->unit_id)?(int)$request->unit_id:null,
            'lesson_id' => isset($request->lesson_id)?(int)$request->lesson_id:null,


            'start_date' => Self::generateCarbonObject($request->start_date) ,
            'end_date' => Self::generateCarbonObject($request->end_date),
        ]);
    }


    public static function fromArray(array $array){
        return new self([
//            'roster_assignment_ids' => isset($array['roster_assignment_ids'])?$array['roster_assignment_ids'] :[],
            'roster_assignment_ids' => isset($array['roster_assignment_ids'])?$array['roster_assignment_ids'] :null,
            'roster_ids' => isset($array['roster_ids'])?$array['roster_ids'] :[],

            'level_subject_id' => isset($array['level_subject_id'])?(int)$array['level_subject_id']:null ,
            'unit_id' => isset($array['unit_id'])?(int)$array['unit_id']:null ,
            'lesson_id' => isset($array['lesson_id'])?(int)$array['lesson_id']:null ,

            'start_date' => isset($array['start_date'])?Self::generateCarbonObject($array['start_date']):null ,
            'end_date' => isset($array['end_date'])?Self::generateCarbonObject($array['end_date']):null,
        ]);
    }




}
