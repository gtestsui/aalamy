<?php

namespace Modules\UsageSteps\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Exceptions\ErrorMsgException;
use App\Http\Traits\ModelSharedScopes;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Assignment\Models\Assignment;
use Modules\HelpCenter\Http\Controllers\Classes\HelpCenterServices;
use Modules\Level\Traits\ModelRelations\LessonRelations;

class UserCompletedStep extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use ModelSharedScopes;
    use LessonRelations;
    protected $table = 'user_completed_steps';

    public static function customizedBooted(){}


    protected $fillable=[
        'user_id',
        'last_step_index',
        'data',

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
