<?php


namespace App\Modules\Notification\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Http\DTO\UserData;
use Modules\User\Models\User;

final class ManualNotificationData extends ObjectData
{
    public ?int       $id=null;
//    public int        $user_id;

    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;

    public string     $subject;
    public string     $content;
    public int        $priority;
    public array      $send_by_types;
    public bool       $all_parents;
    public array      $parent_ids;

    public bool       $all_students;
    public array      $student_ids;

    public bool       $all_teachers;
    public array      $teacher_ids;

    public static function fromRequest(Request $request,User $user): self
    {


        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );
        return new self([

            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,

//            'user_id' => $user->id,
            'subject' => $request->notification_subject,
            'content' => $request->notification_content,
            'priority' => (int)$request->priority,

            'send_by_types' => $request->send_by_types,
            'parent_ids' => isset($request->parent_ids)?$request->parent_ids:[],
            'student_ids' => isset($request->student_ids)?$request->student_ids:[],
            'teacher_ids' => isset($request->teacher_ids)?$request->teacher_ids:[],

            'all_parents' => isset($request->all_parents)?(bool)$request->all_parents:false,
            'all_students' => isset($request->all_students)?(bool)$request->all_students:false,
            'all_teachers' => isset($request->all_teachers)?(bool)$request->all_teachers:false,
        ]);
    }

}
