<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\ModelRelations\EducatorStudentRelations;

class EducatorStudent extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use EducatorStudentRelations;


    public static function customizedBooted(){}


    protected $fillable = [
        'student_id',
        'educator_id',
        'is_active',
        'start_date',

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



    //Scopes
    public function scopeActive($query,bool $status=true){
        return $query->where('is_active',$status);
    }

    //Functions
    public static function linkStudent($studentId,$educatorId,$date=null){
        return Self::create([
            'student_id' => $studentId,
            'educator_id' => $educatorId,
            'start_date' => $date??Carbon::now(),
        ]);
    }


}
