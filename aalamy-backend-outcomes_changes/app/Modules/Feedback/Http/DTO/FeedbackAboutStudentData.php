<?php


namespace Modules\Feedback\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Modules\Feedback\Http\Controllers\Classes\FeedbackServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;

final class FeedbackAboutStudentData extends ObjectData
{

    /**

     * @var mixed|int|null $student_id
     * this filed cant effect while update so that we made it nullable
     * @var Carbon $from_date
     * this filed cant effect while update so that we made it nullable
     * @var Carbon $to_date
     * this filed cant effect while update so that we made it nullable
     */
    public ?int       $id=null;
    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;


    public ?int        $student_id;

    public string     $text;
    public ?Carbon     $from_date;
    public ?Carbon     $to_date;
    public ?bool      $share_with_parent;

    public ?UploadedFile      $file;
    public ?UploadedFile      $image;

    public ?array      $roster_assignment_ids;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,$forUpdate=false): self
    {
        $user = $request->user();
        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );
        /*$schoolId = null;
        $teacherId = null;
        $educatorId = null;
        list(,$accountObject)=UserServices::getAccountTypeAndObject($user,$request->my_teacher_id);
        if(isset($request->my_teacher_id)){
            $teacherId = $accountObject->id;
            $schoolId = $accountObject->school_id;
        }else{
            ${$user->account_type.'Id'} = $accountObject->id;
        }*/

        return new self([
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,

            'student_id' => $forUpdate?null:(int)$request->student_id ,
            'text' => $request->text,
            'from_date' => $forUpdate?null:Self::generateCarbonObject($request->from_date),
            'to_date' => $forUpdate?null:Self::generateCarbonObject($request->to_date),

            'share_with_parent' => isset($request->share_with_parent)?(bool)$request->share_with_parent:false,

            'file' => $request->file,
            'image' => $request->image,
//            'roster_assignment_ids' => isset($request->roster_assignment_ids)?$request->roster_assignment_ids:[],
            'roster_assignment_ids' => FeedbackServices::getTargetRosterAssignments($user,$request->from_date,$request->to_date,$request->roster_assignment_ids),

        ]);
    }


}
