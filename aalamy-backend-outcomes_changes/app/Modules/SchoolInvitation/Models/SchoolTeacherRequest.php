<?php

namespace Modules\SchoolInvitation\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Modules\Notification\Traits\SetTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SchoolInvitation\Traits\ModelRelations\SchoolTeacherRequestRelations;
use Modules\User\Models\User;

class SchoolTeacherRequest extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use SchoolTeacherRequestRelations;


    public static function customizedBooted(){}


    protected $fillable=[
      'educator_id',
      'school_id',
      'status',
      'introductory_message',
      'reject_cause',
      //enum school or educator
      'from',
      //enum school or educator
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

    public function scopeApprove($query){
        return $query->update([
            'status' => 'approved'
        ]);
    }

    /**
     * by request type (received or sent)
     */
    public function scopeByType($query,$requestType,$myAccountType){
        return $query->{$requestType}($myAccountType);
    }



    public function scopeReceived($query,$myAccountType){
        return $query->where('to',$myAccountType);
    }

    public function scopeSent($query,$myAccountType){
        return $query->where('from',$myAccountType);
    }

    public function scopeBelongsTo($query,User $user){
        return $query->where($user->account_type.'_id',$user->{ucfirst($user->account_type)}->id);
    }

}
