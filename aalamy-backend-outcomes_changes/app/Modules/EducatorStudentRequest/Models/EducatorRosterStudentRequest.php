<?php

namespace Modules\EducatorStudentRequest\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\EducatorStudentRequest\Traits\ModelRelations\EducatorRosterStudentRequestRelations;
use Modules\User\Models\User;

class EducatorRosterStudentRequest extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use EducatorRosterStudentRequestRelations;
    protected $table = 'educator_roster_student_requests';

    public static function customizedBooted(){}


    protected $fillable=[
        'educator_id',
        'student_id',
        'roster_id',
        'status',
        'introductory_message',
        'reject_cause',
        'from',
        'to',

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

    ];

    //Attributes



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



    public function scopeReceived($query,$myAccountType){
        return $query->where('to',$myAccountType);
    }

    public function scopeSent($query,$myAccountType){
        return $query->where('from',$myAccountType);
    }


    public function scopeBelongsTo($query,User $user){
    $user->load(ucfirst($user->account_type));
        return $query->where($user->account_type.'_id',$user->{ucfirst($user->account_type)}->id);
    }

    //Functions
    public function approve(){
        return $this->update([
            'status' => config('EducatorStudentRequest.panel.educator_roster_student_request_statuses.approved'),
        ]);
    }

    public function reject(?string $rejectCause=null){
        return $this->update([
            'status' => config('EducatorStudentRequest.panel.educator_roster_student_request_statuses.rejected'),
            'reject_cause' => $rejectCause,
        ]);
    }

}
