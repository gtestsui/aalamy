<?php

namespace Modules\RosterAssignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Student;

class RosterAssignmentStudentAction extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    protected $table = 'roster_assignment_student_actions';

    public static function customizedBooted(){}


    protected $fillable=[
        'roster_assignment_id',
        'student_id',

        'help_request',
        'check_answer_request',

        'deleted',
        'deleted_by_cascade',
        'deleted_at',

    ];

    //Attributes


    //Relations
    public function RosterAssignment(){
        return $this->belongsTo(RosterAssignment::class,'roster_assignment_id');
    }

    public function Student(){
        return $this->belongsTo(Student::class,'student_id');
    }



    //Scopes




    //functions
    public function checkIsHelpRequest(){
        return (bool)$this->help_request;
    }


    public function checkIsCheckAnswerRequest(){
        return (bool)$this->check_answer_request;
    }


}
