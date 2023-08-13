<?php

namespace Modules\Roster\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Roster\Traits\ModelRelations\RosterStudentRelations;

class RosterStudent extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Orderable;
    use RosterStudentRelations;
    protected $table = 'roster_students';

    public static function customizedBooted(){
        static::addGlobalScope('hasActiveClassStudent', function (Builder $builder) {
            $builder->whereHas('ClassStudent',function ($query){
                return $query->active();
            });
        });

    }


    protected $fillable=[
        'roster_id',
        'class_student_id',

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

}
