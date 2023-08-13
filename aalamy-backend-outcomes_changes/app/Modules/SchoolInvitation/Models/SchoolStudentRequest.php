<?php

namespace Modules\SchoolInvitation\Models;

use App\Exceptions\ErrorUnAuthorizationException;
use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Modules\Notification\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SchoolInvitation\Traits\ModelRelations\SchoolStudentRequestRelations;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionConstraints\StudentPermissionClass;
use Modules\TeacherPermission\Http\Controllers\Classes\PermissionServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class SchoolStudentRequest extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use SchoolStudentRequestRelations;

    public static function customizedBooted(){}


    protected $fillable=[
      'student_id',
      'school_id',
      'status',
      'introductory_message',
      'reject_cause',
      'from',
      'to',
      'deleted',
      'deleted_by_cascade',
      'deleted_at',
    ];





    //Scopes
    public function scopeByStatus($query,$status){
        if(is_null($status))
            return $query;
        return $query->where('status',$status);
    }

    /**
     * by request type (received or sent)
     */
    public function scopeByType($query,$requestType,$myAccountType){
        return $query->{$requestType}($myAccountType);
    }

    public function scopeApprove($query){
        return $query->update([
            'status' => 'approved'
        ]);
    }

    public function scopeReceived($query,$myAccountType){
        return $query->where('to',$myAccountType);
    }

    public function scopeSent($query,$myAccountType){
        return $query->where('from',$myAccountType);
    }

    public function scopeBelongsTo($query,User $user){
        if($user->account_type == 'educator' && isset(request()->my_teacher_id)){
            list(,$teacher) = UserServices::getAccountTypeAndObject($user);

            $havePermission = PermissionServices::isHaveOneOfThisPermissions($teacher,[
                'student' => ['approve_or_reject_request_from','create','send_request_to'],
            ]);
            if(!$havePermission)
                throw new ErrorUnAuthorizationException();

            return $query->where('school_id',$teacher->school_id);
        }else{
            return $query->where($user->account_type.'_id',$user->{ucfirst($user->account_type)}->id);

        }
    }

}
