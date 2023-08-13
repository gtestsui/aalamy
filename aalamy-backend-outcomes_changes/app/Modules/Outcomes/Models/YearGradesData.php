<?php

namespace Modules\Outcomes\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Models\BaseLevel;
use Modules\Level\Models\BaseSubject;
use Modules\Outcomes\Traits\ModelRelations\StudentStudyingInformationRelations;

class YearGradesData extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
//    use SoftDelete;
    use Searchable;
//    use StudentStudyingInformationRelations;

    protected $table = 'year_grades_data';

    public static function customizedBooted(){}


    protected $fillable=[
        'year_grade_template_id',
        'student_studying_information_id',
        'exam_degree_semester_1',
        'exam_degree_semester_2',
        'work_degree_semester_1',
        'work_degree_semester_2',
        'total_semester_1',
        'total_semester_2',



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


    public function YearGradeTemplate(){
        return $this->belongsTo(YearGradesTemplate::class);
    }

    public function StudentStudyingInformation(){
        return $this->belongsTo(StudentStudyingInformation::class);
    }

    //Attributes



    //Scopes

}
