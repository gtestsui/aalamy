<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;
use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\ModelRelations\SchoolRelations;

class School extends Model
{
    use DefaultGlobalScopes;
    use HasFactory,ModelSharedScopes,SoftDelete;
    use Searchable;
    use SoftDelete;
    use SchoolRelations;





    public static function customizedBooted(){}


    protected $fillable = [
        'user_id',
        'school_name',
        'bio',
        'is_active',
        'school_image',
        'address_id',
        'allow_student_request',
        'allow_teacher_request',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',

    ];


    public $mySearchableFields = [
        'school_name',
        'bio',
    ];

     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'User',
        'SchoolStudents',
//        'Address',
        'StudentRequests',
        'TeacherInvitations',
        'TeacherRequests',
        'Assignments',
        'ClassInfos',
        'ClassStudents',
        'DiscussionCornerPosts',
        'DiscussionCornerSurveys',
        'Events',
        'FeedbackAboutStudents',
        'QuestionBanks',
        'LearningResources',
        'Topics',
        'GradeBooks',
        'Meetings',
        'LibraryQuestions',
        'Quizzes',
        'Stickers',

    ];


    //Attributes
    public function getSchoolImageAttribute($key){
        if(is_null($key))
            return defaultUserImage('schoolBuilding');
        else
           return baseRoute().$key;
    }



    //Scopes
    public function scopeActive($query){
        return $query->where('is_active',1);
    }

    /**
     * get defined teacher from  my teachers
     */
    public function scopeWithDefinedTeacher($query,User $user){
        return $query->with(['Teachers'=>function($q)use($user){
                return $q->where('user_id',$user->id);
            }]);
    }

    /**
     * get the last teacher request for defined educator
     */
    public function scopeWithDefinedTeacherRequest($query,Educator $educator){
        return $query->with(['TeacherRequests'=>function($q)use($educator){
                return $q->where('educator_id',$educator->id)->orderBy('id','desc')->first();
            }]);
    }

    /**
     * get defined SchoolStudent from  my SchoolStudents
     */
    public function scopeWithDefinedSchoolStudent($query,Student $student){
        return $query->with(['SchoolStudents'=>function($q)use($student){
            return $q->where('student_id',$student->id);
        }]);
    }

    /**
     * get the last student request for defined student
     */
    public function scopeWithDefinedStudentRequest($query,Student $student){
        return $query->with(['StudentRequests'=>function($q)use($student){
            return $q->where('student_id',$student->id)->orderBy('id','desc')->first();
        }]);
    }

    //Functions
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
