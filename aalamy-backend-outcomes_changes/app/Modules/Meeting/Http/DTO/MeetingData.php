<?php


namespace Modules\Meeting\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;

final class MeetingData extends ObjectData
{
    public ?int      $id=null;
    public ?int      $school_id;
    public ?int      $teacher_id;
    public ?int      $educator_id;
    public ?int      $class_id;
    public string    $title;
    public int       $max_participants;
    public string    $moderator_password;
    public string    $attendee_password;
    public Carbon    $date_time;
    public array     $parentIds;
    public array     $studentIds;
    public array     $teacherIds;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request): self
    {
        $user = $request->user();
        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );

        return new self([
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,
            'class_id' => isset($request->class_id)?(int)$request->class_id:null,
            'title' => $request->title,
            'max_participants' => 0/*(int)$request->max_participants*/,
            'moderator_password' => 'moderator'/*$request->moderator_password*/,
            'attendee_password' => 'attendee'/*$request->attendee_password*/,
            'date_time' => Carbon::now(),


            //this arrays either empty[] or have data
//            'all_parents_per_class' => isset($request->all_parents_per_class)?(bool)$request->all_parents_per_class:false,
            'parentIds' =>isset($request->parent_ids)
                ?$request->parent_ids
                :[] ,
//            'all_students_per_class' => isset($request->all_students_per_class)?(bool)$request->all_students_per_class:false,
            'studentIds' =>isset($request->student_ids)
                ?$request->student_ids
                :[] ,
            //just the school who can add event with other teacher
//            'all_teachers' => isset($request->all_teachers)?(bool)$request->all_teachers:false,
            'teacherIds' => isset($request->teacher_ids)&&UserServices::isSchool($user)
                ?$request->teacher_ids
                :[],

        ]);
    }

//    public function allDataForCreate(){
//        return [
//            'school_id'=>$this->school_id,
//            'teacher_id'=>$this->teacher_id,
//            'educator_id'=>$this->educator_id,
//            'title'=>$this->title,
//            'max_participants'=>$this->max_participants,
//        ];
//    }

}
