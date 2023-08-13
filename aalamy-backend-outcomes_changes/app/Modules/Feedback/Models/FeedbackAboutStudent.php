<?php

namespace Modules\Feedback\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Feedback\Traits\ModelRelations\FeedbackAboutStudentRelations;

class FeedbackAboutStudent extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use FeedbackAboutStudentRelations;
    protected $table = 'feedback_about_students';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'educator_id',
        'teacher_id',

        'student_id',
        'text',
        'from_date',
        'to_date',
        'share_with_parent',
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
        'StudentAttendances',
        'Files',
        'Images',

    ];

    protected $mySearchableFields = [
        'text',
        'from_date',
        'to_date',
    ];

    //Attributes





    //Scopes
    public function scopeSharedStatus($query,$status=false){
        return $query->where('share_with_parent',$status);
    }

    public function scopeWithAllRelations($query){
        return $query->with(['StudentAttendances','StudentMarks','Files','Images','Student.User']);
    }

}
