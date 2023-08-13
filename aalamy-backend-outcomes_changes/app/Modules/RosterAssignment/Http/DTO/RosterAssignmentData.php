<?php


namespace Modules\RosterAssignment\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\Assignment\Http\Controllers\Classes\AssignmentSettingClass;
use Modules\RosterAssignment\Models\RosterAssignment;
use Carbon\Carbon;

final class RosterAssignmentData extends ObjectData
{
    public ?int       $id=null;
    public ?int       $assignment_id;
    public ?array     $assignment_ids;
    public ?int       $roster_id;
    public ?array     $roster_ids;

    public bool      $is_locked;
    public bool      $is_hidden;
    public bool      $prevent_request_help;
    public bool      $display_mark;
    public bool      $is_auto_saved;
    public bool      $prevent_moved_between_pages;
    public bool      $is_shuffling;


    public ?Carbon     $start_date;
    public ?Carbon     $expiration_date;

////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,?RosterAssignment $rosterAssignment=null/*,?Assignment $assignment=null*/): self
    {

        //for update just the edited part from settings
        $assignmentSetting = new AssignmentSettingClass($request);

        //either get data from request if its sent
        // or use the rosterAssignment data as default
        if(!is_null($rosterAssignment)){
            $assignmentSetting->prepareAssignmentSetting($rosterAssignment,$request);
        }


        return new self(array_merge($assignmentSetting->all(),[
            'assignment_id' => isset($request->assignment_id)?(int)$request->assignment_id :null,
            'assignment_ids' => $request->assignment_ids ,
            'roster_id' => isset($request->roster_id)?(int)$request->roster_id:null ,
            'roster_ids' => $request->roster_ids ,

//            'start_date' => Self::generateCarbonObject($request->start_date,true) ,
            'start_date' => isset($request->start_date)
                ?ServicesClass::toTimezone($request->start_date,$request->time_zone,config('panel.timezone'))
                :new Carbon($rosterAssignment->start_date),
//            'expiration_date' => Self::generateCarbonObject($request->expiration_date,true ),
            'expiration_date' => ServicesClass::toTimezone($request->expiration_date,$request->time_zone,config('panel.timezone')),
        ]));
    }

    public function allSettings(){
        return [

            'is_locked' => $this->is_locked,

            'is_hidden' => $this->is_hidden,

            'prevent_request_help' => $this->prevent_request_help,

            'display_mark' => $this->display_mark,

            'is_auto_saved' => $this->is_auto_saved,

            'prevent_moved_between_pages' => $this->prevent_moved_between_pages,

            'is_shuffling' => $this->is_shuffling,

        ];
    }


}
