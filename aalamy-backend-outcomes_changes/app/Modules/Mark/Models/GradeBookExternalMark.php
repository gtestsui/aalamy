<?php

namespace Modules\Mark\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Modules\Mark\Traits\ModelRelations\GradeBookExternalMarkRelations;


class GradeBookExternalMark extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use GradeBookExternalMarkRelations;

    protected $collection  = 'grade_book_external_marks';

    public static function customizedBooted(){}


    protected $fillable=[
        'grade_book_id',
        'student_id',
        'mark',


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
