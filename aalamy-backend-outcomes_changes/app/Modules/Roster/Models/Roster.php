<?php

namespace Modules\Roster\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Roster\Traits\ModelRelations\RosterRelations;

class Roster extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use RosterRelations;
    protected $table = 'rosters';

    public static function customizedBooted(){}


    protected $fillable=[
        'class_info_id',
        'created_by_teacher_id',
        'created_by_school_id',
        'created_by_educator_id',
        'name',
        'description',
        'color',
        'code',
        'is_closed',

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
        'RosterStudents',
        'EducatorRosterStudentRequests',
        'RosterAssignments',
        'GradeBooks',
        'Quizzes',

    ];

    protected $mySearchableFields = [
        'name',
        'description',
        'color',
    ];

    //Attributes




    //Scopes

    //Functions
    public function closeOrUnClose(){
        $this->update([
            'is_closed' => !$this->is_closed
        ]);
    }

    public function isClosed(){
        return $this->is_closed;
    }

}
