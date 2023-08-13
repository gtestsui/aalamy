<?php

namespace Modules\Quiz\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Http\DTO\FilterQuizData;
use Modules\Quiz\Traits\ModelRelations\QuizRelations;

class Quiz extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use QuizRelations;

    protected $table = 'quizzes';

    public static function customizedBooted(){
//dump('quiz');
//dump(request());
        //        /**
//         * @see removeWithoutDeletedItemsScope
//         */
//        if(UserServices::checkShouldRemoveWithoutDeletedItemsScope())
//            static::removeWithoutDeletedItemsScope();


    }


    protected $fillable=[
        'school_id',
        'educator_id',
        'teacher_id',
        'roster_id',
        'level_subject_id',
        'unit_id',
        'lesson_id',
        'prevent_display_answers',
        'name',
        'mark',
        'questions_count',
        'time',//default in minutes
        'time_type',

        'start_date',//date time
        'end_date',//date time

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
        'GradeBookQuizzes',
        'Questions',
        'QuizStudents',
    ];

    protected $mySearchableFields = [

        'name',
        'questions_count',
        'time',//default in minutes

        'start_date',//date time
        'end_date',//date time

    ];

    //Attributes



    //Scopes
    public function scopeFilter($query,?FilterQuizData $data=null){
        return $query
            ->when(!is_null($data),function ($query)use($data){
                return $query
                    ->when(isset($data->quizzes_ids),function ($query)use($data){
                        return $query->whereIn('id',$data->quizzes_ids);
                    })
//                    ->when(is_null($data->quizzes_ids),function ($query)use($data){
//                        return $query->where('id',-1);
//                    })
                    ->when(isset($data->level_subject_id),function ($query)use($data){
                        return $query->where('level_subject_id',$data->level_subject_id);
                    })
                    ->when(isset($data->unit_id),function ($query)use($data){
                        return $query->where('unit_id',$data->unit_id);
                    })
                    ->when(isset($data->lesson_id),function ($query)use($data){
                        return $query->where('lesson_id',$data->lesson_id);
                    });
            });

    }

}
