<?php

namespace Modules\Feedback\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Feedback\Traits\ModelRelations\FeedbackAboutStudentAttendanceRelations;
use Modules\Feedback\Traits\ModelRelations\FeedbackAboutStudentMarkRelations;

class FeedbackAboutStudentMark extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use FeedbackAboutStudentMarkRelations;

    protected $table = 'feedback_about_student_marks';

    public static function customizedBooted(){}


    protected $fillable=[
        'feedback_id',
        'mark_file',

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
    public function getMarkFileAttribute($key){
        if(is_null($key))
            return null;
        return baseRoute().$key;
    }




    //Scopes

}
