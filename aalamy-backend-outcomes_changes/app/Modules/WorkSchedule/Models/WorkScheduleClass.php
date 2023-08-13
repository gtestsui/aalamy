<?php

namespace Modules\WorkSchedule\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ClassModule\Models\ClassInfo;
use Modules\Roster\Traits\ModelRelations\RosterStudentRelations;

class WorkScheduleClass extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
//    use SoftDelete;
    use Orderable;

    protected $table = 'work_schedule_classes';


    protected $fillable=[
        'week_day_id',
        'class_id',
        'class_info_id',
        'period_number',
        'start',
        'end',
//
//        'deleted',
//        'deleted_by_cascade',
//        'deleted_at',
    ];

    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [

    ];



    public function ClassInfo(){
        return $this->belongsTo(ClassInfo::class);
    }

    //Attributes



    //Scopes

}
