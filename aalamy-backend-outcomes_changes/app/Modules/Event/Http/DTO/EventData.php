<?php


namespace Modules\Event\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Teacher;
use Modules\User\Models\User;
use Carbon\Carbon;

final class EventData extends ObjectData
{
    public ?int       $id=null;
    public ?int       $school_id;
    public ?int       $teacher_id;
    public ?int       $educator_id;
    public string     $name;
    public Carbon     $date;
    public bool      $all_parents;
    public array     $parentIds;
    public bool      $all_students;
    public array     $studentIds;
    public bool      $all_teachers;
    public array     $teacherIds;
////    public ?Carbon   $created_at;

    public static function fromRequest(Request $request,User $user): self
    {

        list($schoolId,$teacherId,$educatorId) = UserServices::prepareOnwer(
            $user,$request
        );
        /*if(isset($request->my_teacher_id)){
            $teacher = Teacher::findOrFail($request->my_teacher_id);
            $teacherId = $teacher->id;
            $schoolId = $teacher->school_id;
        }else{
            ${$user->account_type.'Id'} = $user->{ucfirst($user->account_type)}->id;

        }*/

        return new self([
            'school_id' => $schoolId ,
            'teacher_id' => $teacherId,
            'educator_id' => $educatorId,
            'name' => $request->name,
//            'date' => Parent::generateCarbonObject($request->date,true),
            'date' => ServicesClass::toTimezone($request->date,$request->time_zone,config('panel.timezone')),

            //this arrays either empty[] or have data
            'all_parents' => isset($request->all_parents)?(bool)$request->all_parents:false,
            'parentIds' =>isset($request->parent_ids)
                            ?$request->parent_ids
                            :[] ,
            'all_students' => isset($request->all_students)?(bool)$request->all_students:false,
            'studentIds' =>isset($request->student_ids)
                            ?$request->student_ids
                            :[] ,
            //just the school who can add event with other teacher
            'all_teachers' => isset($request->all_teachers)?(bool)$request->all_teachers:false,
            'teacherIds' => isset($request->teacher_ids)&&UserServices::isSchool($user)
                            ?$request->teacher_ids
                            :[],
        ]);
    }

    public function allWithoutRelations(): array
    {
        return [
            'school_id'  => $this->school_id,
            'teacher_id'    => $this->teacher_id,
            'educator_id'=> $this->educator_id,
            'name'       => $this->name,
            'date'   => $this->date,

        ];
    }
}
