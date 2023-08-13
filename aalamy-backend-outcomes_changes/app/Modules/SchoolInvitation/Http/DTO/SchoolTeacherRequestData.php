<?php


namespace Modules\SchoolInvitation\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class SchoolTeacherRequestData extends ObjectData
{
    public ?int      $id=null;
    public ?int      $educator_id;
    public ?int      $school_id;
//    public string    $status;
    public ?string    $introductory_message;
//    public ?string    $reject_cause;
    public string    $from;
    public string    $to;
//    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,&$user): self
    {
        $requestFrom = $user->account_type;
        if($requestFrom=='educator'){
            $requestEducatorId = $user->Educator->id;
            $requestSchoolId = $request->school_id;
            $requestTo = 'school';
        }else{
            $requestEducatorId = $request->educator_id;
            $requestSchoolId = $user->School->id;
            $requestTo = 'educator';

        }

        return new self([
            'educator_id' => (int)$requestEducatorId,
            'school_id' => (int)$requestSchoolId,
            'introductory_message' =>  $request->introductory_message,
//            'reject_cause' => $request->reject_cause,
            'from' => $requestFrom,
            'to' => $requestTo
        ]);
    }

}
