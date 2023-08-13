<?php

namespace Modules\Meeting\Models;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Controllers\Classes\ApplicationModules;
use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Assignment\Models\Assignment;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\Level\Traits\ModelRelations\LessonRelations;
use Modules\Meeting\Traits\ModelRelations\MeetingRelations;

class Meeting extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use MeetingRelations;
    protected $table = 'meetings';

    public static function customizedBooted(){
        //stop the global scope DefaultOrderByScope
        static::addGlobalScope('orderByDate', function (Builder $builder) {
            $builder->withoutGlobalScope(DefaultOrderByScope::class)->orderBy('date_time', 'desc');
        });
    }


    protected $fillable=[
        'school_id',
        'teacher_id',
        'educator_id',
        'class_id',
        'title',
        'max_participants',
        'moderator_password',
        'attendee_password',
        'date_time',

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
        'TargetUsers',
    ];

    private $mySearchableFields = [
        'title',
        'max_participants',
        'date_time',
    ];

    //Attributes



    //Scopes
    public function scopeItsTargeteMe($query,$accountType,$accountObject){
        return $query->whereHas('TargetUsers',function ($query)use($accountType,$accountObject){
            return $query->where("{$accountType}_id",$accountObject->id);
        });

    }

    public function scopeMyOwnAsSchool($query,$teacherIds,$schoolId){
        return $query->where(function ($query)use ($teacherIds,$schoolId){
            return $query->where('school_id',$schoolId)
                ->orWhereIn('teacher_id',$teacherIds);
        });
    }

    public function scopeMyOwnAsEducator($query,$educatorId){
        return $query->where(function ($query)use ($educatorId){
            return $query->where('educator_id',$educatorId);
        });
    }

    public function scopeMyOwnAsTeacher($query,$teacherId){
        return $query->where(function ($query)use ($teacherId){
            return $query->where('teacher_id',$teacherId);
        });
    }

    public function scopeWithAllRelations($query){
        return $query->with(['Educator.User','Teacher.User','School.User']);
    }

    //Functions
    public function imTheModerator($accountType,$accountObject):bool{
        if($this->{$accountType.'_id'} == $accountObject->id)
            return true;
        return false;
    }

    /**
     * @return bool|MeetingTargetUser
     */
    public function imFromAttendees($accountType,$accountObject){
        if($accountType == configFromModule('panel.all_account_types.school',ApplicationModules::USER_MODULE_NAME))
            throw new ErrorUnAuthorizationException();
        $target = MeetingTargetUser::where("{$accountType}_id",$accountObject->id)->first();
        if(!is_null($target))
            return $target;
        return false;
    }

    public function checkItsTargetMe($accountType,$accountObject):bool{
        if($this->{$accountType.'_id'} == $accountObject->id)
            return true;
        return false;
    }


}
