<?php

namespace Modules\Meeting\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Meeting\Traits\ModelRelations\MeetingTargetUserRelations;

class MeetingTargetUser extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use MeetingTargetUserRelations;
    protected $table = 'meeting_target_users';

    public static function customizedBooted(){}


    protected $fillable=[
        'meeting_id',
        'parent_id' ,
        'student_id',
        'teacher_id',
        'attendee_status',
        'note',
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

    private $mySearchableFields = [

    ];

    //Attributes



    //Scopes
    public function scopeWithAllRelations($query){
        return $query->with(['Teacher.User','Student.User','Parent.User']);
    }

    public function scopeIsPresent($query,bool $status=true){
        return $query->where('attendee_status',$status);
    }


    //Functions

    public function oppositeAttendeeStatus(){
        if($this->attendee_status){
            $this->makeAsAbsent();
        }else{
            $this->makeAsPresent();

        }
    }

    public function makeAsPresent(){
        $this->update([
           'attendee_status' => true
        ]);
    }

    public function makeAsAbsent(){
        $this->update([
            'attendee_status' => false
        ]);
    }

}
