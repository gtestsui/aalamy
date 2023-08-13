<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;

class StudentOtherInformation extends Model
{
    use DefaultGlobalScopes;

    use SoftDelete;
    use Searchable;
    use Orderable;
    use SoftDelete;


    public static function customizedBooted(){}


    protected $fillable = [
        'student_id',
        'aid_provided_to_the_student',//المساعدات المقدمة للتلميذ
        'underwent_early_childhood_program',//خضع لبرنامج الطفولة المبكرة
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];

	 protected $casts = [
        'underwent_early_childhood_program' => 'boolean',
    ];

    private $mySearchableFields = [

    ];


     /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [


    ];



}
