<?php

namespace Modules\Mark\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Event\Traits\ModelRelations\EventRelations;

use Jenssegers\Mongodb\Eloquent\Model;

class MongoStudentAnswer extends Model
{
//    use DefaultGlobalScopes;
    use HasFactory;
//    use SoftDelete;
//    use EventRelations;

    protected $connection  = 'mongodb';
    protected $collection  = 'std_answer';

    public static function customizedBooted(){}


    protected $fillable=[
        'student_id',
        'user_id',
        'answer_body',
        'assignment_id',
        'roster_assignment_id',
        'roster_id',
        'page_id',
    ];


    protected $dates = ['created_at', 'updated_at'];


    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [
//        'TargetUsers',

    ];

    //Attributes




    //Scopes


}
