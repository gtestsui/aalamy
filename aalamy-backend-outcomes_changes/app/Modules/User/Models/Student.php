<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Roster\Models\EducatorRosterStudentRequest;
use Modules\User\Traits\ModelRelations\StudentRelations;

class Student extends Model
{
    use DefaultGlobalScopes;

    use HasFactory,SoftDelete,ModelSharedScopes;
    use Searchable;
    use Orderable;
    use SoftDelete;
    use StudentRelations;


    public static function customizedBooted(){}


    protected $fillable = [
        'user_id',
        'type',
        'parent_email',
        'parent_code',
        'created_by_teacher',//nullable and it's not nullable if the user created by another account
        'created_by_school',//nullable and it's not nullable if the user created by another account
        'created_by_educator',//nullable and it's not nullable if the user created by another account
        'is_active',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

//    private $mySearchableFields = [
//        'type',
//        'parent_email',
//    ];

    private $mySearchableFields = [
        'type',
        'parent_email',
    ];


     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
//        'User',
        'ParentStudents',
        'SchoolStudent',
        'ClassStudents',
        'EducatorRosterStudentRequests',
        'EducatorStudents',
        'TargetUsers',
        'FeedbackAboutStudents',
        'SchoolRequests',
        'Achievements',
        'GradeBookExternalMarks',
        'MeetingTargetUsers',
        'QuizStudents',
        'RosterAssignmentStudentActions',
        'RosterAssignmentStudentAttendances',
        'RosterAssignmentStudentPages',
        'StudentPageStickers',

    ];

    public function setParentEmailAttribute($key){
        $this->attributes['parent_email'] =
            is_null($key)
                ?$key
                :strtolower($key);

    }


    //Scopes
    public function scopeActive($query){
        return $query->where('is_active',1);
    }

    public function scopeBelongsToSchool($query,$schoolId){
        return $query->whereHas('SchoolStudent',function ($query)use ($schoolId){
            return $query->where('school_id',$schoolId);
        });
    }

    public function scopeBelongsToEducator($query,$educatorId){
        return $query->whereHas('EducatorStudents',function ($query)use ($educatorId){
            return $query->where('educator_id',$educatorId);
        });
    }

    /**
     * get defined schoolStudent from my schoolStudent
     */
    public function scopeWithDefinedSchoolStudent($query,School $school){
        return $query->with(['SchoolStudent'=>function($q)use($school){
            return $q->where('school_id',$school->id)->orderBy('id','desc');
        }]);
    }

    /**
     * get the last school request for defined school
     */
    public function scopeWithDefinedSchoolRequest($query,School $school){
        return $query->with(['SchoolRequests'=>function($q)use($school){
            return $q->where('school_id',$school->id)->orderBy('id','desc')->first();
        }]);
    }

    /**
     * get defined EducatorStudent from my EducatorStudents
     */
    public function scopeWithDefinedEducatorStudent($query,Educator $educator){
        return $query->with(['EducatorStudents'=>function($q)use($educator){
            return $q->where('educator_id',$educator->id)->orderBy('id','desc');
        }]);
    }

    /**
     * get the last EducatorRosterStudent request for defined educator
     */
    public function scopeWithDefinedEducatorRosterStudentRequest($query,Educator $educator){
        return $query->with(['EducatorRosterStudentRequests'=>function($q)use($educator){
            return $q->where('educator_id',$educator->id)->orderBy('id','desc')->first();
        }]);
    }


    //functions
    public function activateOrDeActivate(){
        if(!$this->is_active)
            return $this->update([
                'is_active' => 1
            ]);
        else
            return $this->update([
                'is_active' => 0
            ]);
    }

    public function activate(){
        return $this->update([
            'is_active' => 1
        ]);
    }

    public function deactivate(){
        return $this->update([
            'is_active' => 0
        ]);
    }




}
