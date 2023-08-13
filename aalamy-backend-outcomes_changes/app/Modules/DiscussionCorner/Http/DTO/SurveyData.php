<?php


namespace Modules\DiscussionCorner\Http\DTO;


use App\Http\Controllers\Classes\ServicesClass;
use App\Http\Controllers\DTO\Parents\ObjectData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

final class SurveyData extends ObjectData
{
    public ?int      $id=null;
    public int       $user_id;
    public ?int      $school_id;
    public ?int      $educator_id;
    public string    $subject;
    public string    $description;
    public int       $priority;
    public string    $user_type;
    public bool      $is_approved;
    public array     $questions;
////    public ?Carbon   $created_at;
//
    public static function fromRequest(Request $request,bool $forUpdate=false): self
    {
        $user = $request->user();
        //check if im trying to add in my corner the make it approved auto
        $is_approved = false;
        if(isset($request->school_id) && UserServices::isSchool($user))
            $is_approved = true;
        if(isset($request->educator_id) && UserServices::isEducator($user))
            $is_approved = true;

        return new self([
            'user_id'    => (int)$user->id,
            'school_id'   => isset($request->school_id)&&!$forUpdate
                ?(int)$request->school_id
                :null,
            'educator_id' => isset($request->educator_id)&&!$forUpdate
                ?(int)$request->educator_id
                :null,
            'subject'        => $request->subject,
            'description'    => $request->description,
//            'priority'    => (string)config('DiscussionCorner.panel.survey_priority_values')[$request->priority],
            'priority'    => (int)$request->priority,
            'user_type'   => $user->account_type,
            'is_approved'  => (bool)$is_approved,
            'questions' => $request->questions,

        ]);
    }


    public function allWithoutRelations(): array
    {
        return [
            'user_id'    => $this->user_id,
            'school_id'  => $this->school_id,
            'educator_id'=> $this->educator_id,
            'subject'       => $this->subject,
            'description'       => $this->description,
            'priority'   => $this->priority,
            'user_type'  => $this->user_type,
            'is_approved'=> $this->is_approved,

        ];
    }

}
