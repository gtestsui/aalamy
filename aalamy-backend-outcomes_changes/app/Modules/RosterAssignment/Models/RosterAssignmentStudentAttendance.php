<?php

namespace Modules\RosterAssignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Student;

class RosterAssignmentStudentAttendance extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    protected $table = 'roster_assignment_students_attendance';

    public static function customizedBooted(){}


    protected $fillable=[
        'roster_assignment_id',
        'student_id',

        'attendee_status',
        'note',

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
    public function scopeFilterByStudent($query,$studentId){
        if(is_null($studentId))
            return $query;
        return $query->where('student_id',$studentId);
    }

    public function scopeWithAllRealtions($query){
        return $query->with(['Student.User','RosterAssignment.Assignment']);
    }

    public function scopeIsAttendee($query,$status=true){
        return $query->where('attendee_status',$status);
    }

    //functions
    public function markAsPresent(){
        $this->update([
           'attendee_status' => true,
        ]);
    }


}
