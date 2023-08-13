<?php

namespace Modules\RosterAssignment\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use App\Scopes\DefaultOrderByScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Mark\Models\GradeBookRosterAssignment;
use Modules\RosterAssignment\Http\DTO\FilterRosterAssignmentData;

class RosterAssignment extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    protected $table = 'roster_assignments';

    public static function customizedBooted(){}


    protected $fillable=[
        'assignment_id',
        'roster_id',

        'is_locked',
        'is_hidden',
        'prevent_request_help',
        'display_mark',
        'is_auto_saved',
        'prevent_moved_between_pages',
        'is_shuffling',

        'start_date',
        'expiration_date',
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
        'StudentActions',
        'RosterAssignmentPages',
        'RosterAssignmentStudentAttendances',
        'GradeBookRosterAssignments',
    ];

    //Attributes


    //Relations
    public function Assignment(){
        return $this->belongsTo('Modules\Assignment\Models\Assignment','assignment_id');
    }

    public function AvailableAssignment(){
        return $this->belongsTo('Modules\Assignment\Models\Assignment','assignment_id')
//            ->isLocked(false)
            ->isHidden(false);
    }

    public function Roster(){
        return $this->belongsTo('Modules\Roster\Models\Roster','roster_id');
    }

    public function StudentActions(){
        return $this->hasMany(RosterAssignmentStudentAction::class,'roster_assignment_id');
    }

    public function RosterAssignmentPages(){
        return $this->hasMany(RosterAssignmentPage::class,'roster_assignment_id');
    }

    public function RosterAssignmentStudentAttendances(){
        return $this->hasMany(RosterAssignmentStudentAttendance::class,'roster_assignment_id');
    }

    public function GradeBookRosterAssignments(){
            return $this->hasMany(GradeBookRosterAssignment::class,'roster_assignment_id');
    }


    //Scopes
    public function scopeIsLocked($query,$status=false){
        return $query->where('is_locked',$status);
    }


    public function scopeIsHidden($query,$status=false){
        return $query->where('is_hidden',$status);
    }


    public function scopeByMonthFromStartDate($query,Carbon $date){
        return $query->whereMonth('start_date',$date->month);
    }

    public function scopeByDayFromStartDate($query,Carbon $date){
        return $query->whereDay('start_date',$date->day);
    }

    public function scopeWithAllRelations($query){
        return $query->with(['Assignment','Roster']);

    }

    public function scopeFilter($query,?FilterRosterAssignmentData $data=null){

        return $query
            ->when(!is_null($data),function ($query)use($data){
                return $query->when(!is_null($data->start_date) ,function ($query)use($data){
//                    return $query->whereDate('start_date','>=',$data->start_date);
                    return $query->where('start_date','>=',$data->start_date);
                })
                ->when(!is_null($data->end_date),function ($query)use($data){
//                    return $query->whereDate('expiration_date','<=',$data->end_date);
                    return $query->where('expiration_date','<=',$data->end_date);
                })
                ->when(isset($data->roster_assignment_ids),function ($query)use($data){
                    return $query->whereIn('id',$data->roster_assignment_ids);
                })
//                ->when(is_null($data->roster_assignment_ids),function ($query)use($data){
//                    return $query->where('id',-1);
//                })
                ->when(isset($data->level_subject_id),function ($query)use($data){
                    return $query->whereHas('Assignment',function ($query)use ($data){
                        return $query->where('level_subject_id',$data->level_subject_id);
                    });
                })
                ->when(isset($data->unit_id),function ($query)use($data){
                    return $query->whereHas('Assignment',function ($query)use ($data){
                        return $query->where('unit_id',$data->unit_id);
                    });
                })
                ->when(isset($data->lesson_id),function ($query)use($data){
                    return $query->whereHas('Assignment',function ($query)use ($data){
                        return $query->where('lesson_id',$data->lesson_id);
                    });
                });
            });

    }

    /**
     * return the count of students where have requested for help
     * and where have request for check_answer
     */
    public function scopeWithStudentActionsStatistics($query){
        return $query->with(['StudentActions'=>function($query){
            return $query->withoutGlobalScope(DefaultOrderByScope::class)
                ->select(DB::raw('roster_assignment_id,Sum(help_request) as help_requests_count,Sum(check_answer_request) as check_answer_requests_count'))
                ->groupBy('roster_assignment_id');

        }]);
    }

    //functions
    public function checkIsHidden(){
        if($this->is_hidden)
            return true;
        return false;
    }

    public function checkIsLocked(){
        if($this->is_locked)
            return true;
        return false;
    }

}
