<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\ModelRelations\EducatorRelations;

class Educator extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use Orderable;
    use SoftDelete;
    use EducatorRelations;



    public static function customizedBooted(){}


    protected $fillable = [
        'user_id',
        'bio',
        'is_active',
        'certificate',

        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
        'User',
        'Assignments',
        'ClassInfos',
        'ClassStudents',
        'DiscussionCornerPosts',
        'DiscussionCornerSurveys',
        'SchoolRequests',
        'EducatorRosterStudentRequests',
        'EducatorStudents',
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

    private $mySearchableFields = [
        'bio',
    ];


    //Attributes
    public function getCertificateAttribute($key)
    {
        if(isset($key))
            return baseRoute().$key;
        else
            return $key;
    }





    //Scopes
    public function scopeActive($query){
        return $query->where('is_active',1);
    }

    /**
     * get the last school request for defined school
     */
    public function scopeWithDefinedSchoolRequest($query,School $school){
        return $query->with(['SchoolRequests'=>function($q)use($school){
            return $q->where('school_id',$school->id)->orderBy('id','desc')->first();
        }]);
    }

    //Functions
    public function activateOrDeActivate(){
        if(!$this->is_active)
            return $this->activate();
        else
            return $this->deactivate();
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
