<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Traits\ModelRelations\SchoolStudentRelations;

class SchoolStudent extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;
    use SchoolStudentRelations;


    public static function customizedBooted(){}


    protected $fillable = [
        'student_id',
        'school_id',
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
//        'Student',
//        'School',

    ];

    protected $mySearchableFields = [
        'start_date',
    ];



    //Scopes
    public function scopeActive($query,$status=true){
        return $query->where('is_active',$status);
    }

    public function scopeActivate($query){
        return $query->update([
            'is_active' => true,
        ]);
    }

    public function scopeUnActivate($query){
        return $query->update([
           'is_active' => false,
        ]);
    }

    //Functions
    public static function linkStudent($studentId,$schoolId,$date=null){
        return Self::create([
            'student_id' => $studentId,
            'school_id' => $schoolId,
            'start_date' => $date??Carbon::now(),
        ]);
    }


}
