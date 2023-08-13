<?php

namespace Modules\User\Models;


use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Traits\ModelRelations\TeacherRelations;

class Teacher extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use SoftDelete;
    use TeacherRelations;


    public static function customizedBooted(){

//        dump(request()->route('soft_delete'));
//        /**
//         * @see removeWithoutDeletedItemsScope
//         */
//        if(UserServices::checkShouldRemoveWithoutDeletedItemsScope())
//            static::removeWithoutDeletedItemsScope();


    }


    protected $fillable = [
        'user_id',
        'school_id',
        'bio',
        'is_active',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

    private $mySearchableFields = [
        'bio',
    ];

     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
//        'User',
//        'School',
        'ClassInfos',
        'Assignments',
//        'ClassStudents',//because if the teacher add student to class then when the teacher is deleted the student shouldnt delete
        'Events',
        'TargetUsers',
        'FeedbackAboutStudents',
        'QuestionBanks',
        'LearningResources',
        'Topics',
        'GradeBooks',
        'Meetings',
        'MeetingTargetUsers',
        'LibraryQuestions',
        'Quizzes',
        'Stickers',

    ];



    //Scopes
    public function scopeActive($query){
        return $query->where('is_active',1);
    }

    public function scopeBelongToMe($query,$user){

        if($user->account_type == configFromModule('panel.all_account_types.school',ApplicationModules::USER_MODULE_NAME)){
            $school = $user->School;
            return $query->belongToSchool($school->id);
        }else{
            return $query->belongToEducator($user->id);
        }

    }


    public function scopeDefinedEducatorBelongToSchool($query,$userId,$schoolId){
        return $query->where('user_id',$userId)
            ->where('school_id',$schoolId)
            ->active();
    }

    public function scopeBelongToSchool($query,$schoolId){
        return $query->where('school_id',$schoolId)
            ->with('User')
            ->active();
    }

    public function scopeBelongToEducator($query,$userId){
        return $query->where('user_id',$userId)
            ->with('School')
            ->active();
    }


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
