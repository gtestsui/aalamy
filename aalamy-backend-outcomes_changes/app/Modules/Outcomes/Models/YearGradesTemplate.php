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

class YearGradesTemplate extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
//    use SoftDelete;
    use Searchable;
//    use StudentStudyingInformationRelations;

    protected $table = 'year_grades_templates';

    public static function customizedBooted(){}


    protected $fillable=[
        'base_level_id',
        'base_subject_id',
        'writable_subject_name',
        'max_degree',
        'failure_point',
        'order',
        'its_tow_section_subject',
        'its_one_mark',//like السلوك,النشاط
        'its_grand_total',//المجموع العام
        'its_final_total',//المجموع النهائي


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


    public function BaseSubject(){
        return $this->belongsTo(BaseSubject::class);
    }

    public function BaseLevel(){
        return $this->belongsTo(BaseLevel::class);
    }

    public function Marks(){
        return $this->hasMany(Mark::class,'year_grade_template_id');
    }

    public function YearGradeData(){
        return $this->hasOne(YearGradesData::class,'year_grade_template_id');
    }

    //Attributes



    //Scopes

}
