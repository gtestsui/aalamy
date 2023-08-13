<?php

namespace Modules\Outcomes\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Models\LevelSubject;
use Modules\Level\Models\Subject;

class Mark extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;


	const VERBAL_PERCENTAGE = 10;
    const JOBS_AND_WORK_SHEETS_PERCENTAGE = 10;
    const ACTIVITIES_AND_INITIATIVES_PERCENTAGE = 20;
    const QUIZ_PERCENTAGE = 20;
    const EXAM_PERCENTAGE = 40;

    protected $table = 'marks';

    public static function customizedBooted(){}


    protected $fillable=[
        'year_grade_template_id',
        'student_studying_information_id',
        'subject_id',
        'level_subject_id',

        'its_one_field',
        'verbal',
        'jobs_and_worksheets',
        'activities_and_Initiatives',
        'quiz',
        'exam',
        'final_mark',

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


    /**
     * @var string[] $parentRelations
     * when the model belongs to another  parent model
     * and the model and his parent are deleted
     * andddd I can't restore the model if the parent is deleted
     * then I should fill $parentRelations array by
     * the relation name to that parent model
     * to prevent restore that model
     */
    protected $parentRelations = [

    ];

    private $mySearchableFields = [

    ];


    public function Subject(){
        return $this->belongsTo(Subject::class);
    }


    public function LevelSubject(){
        return $this->belongsTo(LevelSubject::class);
    }

    //Attributes



    //Scopes

}
