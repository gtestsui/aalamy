<?php

namespace Modules\Outcomes\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Outcomes\Traits\ModelRelations\StudentStudyingInformationRelations;

class StudentStudyingInformation extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use StudentStudyingInformationRelations;

    protected $table = 'student_studying_information';

    public static function customizedBooted(){}


    protected $fillable=[
        'student_id',
        'school_id',
        'level_id',//the student will use the system in level 1,2,3,.. so this field will be helpful
        'study_year',
//        'study_year_id',

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
        'Marks',

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
      'User'
    ];

    private $mySearchableFields = [

    ];

    //Attributes



    //Scopes

}
