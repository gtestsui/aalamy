<?php


namespace Modules\SchoolInvitation\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class SchoolStudentRequestData extends ObjectData
{
    public ?int      $id=null;
    public ?int      $student_id;
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
        if($requestFrom=='student'){
            $requestStudent_id = $user->Student->id;
            $requestSchool_id = $request->school_id;
            $requestTo = 'school';
        }else{
            $requestFrom='school';
            $requestStudent_id = $request->student_id;
            $requestTo = 'student';

            if(isset($request->my_teacher_id)){
                list(,$teacher) = UserServices::getAccountTypeAndObject($user);
                $requestSchool_id = $teacher->school_id;
            }else{
                $requestSchool_id = $user->School->id;
            }


        }

        return new self([
            'student_id' => (int)$requestStudent_id,
            'school_id' => (int)$requestSchool_id,
            'introductory_message' =>  $request->introductory_message,
//            'reject_cause' => $request->reject_cause,
            'from' => $requestFrom,
            'to' => $requestTo,

        ]);
    }

}
