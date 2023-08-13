<?php

namespace Modules\Mark\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Modules\Mark\Traits\ModelRelations\GradeBookRosterAssignmentRelations;


class GradeBookRosterAssignment extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use GradeBookRosterAssignmentRelations;

    protected $collection  = 'grade_book_roster_assignments';

    public static function customizedBooted(){}


    protected $fillable=[
        'grade_book_id',
        'roster_assignment_id',
        'weight',

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
