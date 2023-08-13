<?php


namespace Modules\WorkSchedule\Http\DTO;


use App\Exceptions\ErrorMsgException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class WorkScheduleClassData extends ObjectData
{
    public ?int      $id=null;
    public ?int      $class_id;
    public ?int      $class_info_id;
    public ?int      $week_day_id;
    public ?int      $period_number;
    public string    $start;
    public string    $end;
    public bool      $delete_it;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,$forUpdate=false): self
    {

        $user = $request->user();
        list($accountType,$accountObject) = UserServices::getAccountTypeAndObject($user);


        $start = !$forUpdate
            ?configFromModule(
                "panel.periods_start_times.$request->period_number.start",
                ApplicationModules::WORK_SCHEDULE_NAME
            )
            :null;

        $end = !$forUpdate
            ?configFromModule(
                "panel.periods_start_times.$request->period_number.end",
                ApplicationModules::WORK_SCHEDULE_NAME
            )
            :null;

        return new self([
            'class_id' => !$forUpdate?(int)$request->class_id:null,
            'class_info_id' => (int)$request->class_info_id,
            'week_day_id' => !$forUpdate?(int)$request->week_day_id:null,
            'period_number' => !$forUpdate?(int)$request->period_number:null,
            'start' => $start,
            'end' => $end,
        	'delete_it' => isset($request->delete_it)&&is_bool($request->delete_it)
                ?(bool)$request->delete_it
                :false,
        ]);
    }

}
