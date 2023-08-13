<?php

namespace Modules\ClassModule\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\ClassModule\Traits\ModelRelations\ClassStudentRelations;
use Modules\Roster\Models\RosterStudent;

class ClassStudent extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use Searchable;
    use SoftDelete;
    use ClassStudentRelations;

    public static function customizedBooted(){}


    /**
     * when is_active true that mean the student currently in this class
     * (and maybe can be in many classes at same time?)
     */
    protected $fillable=[
        'class_id',
        'student_id',

        //who add the student to class
        'teacher_id',//nullable
        'school_id',//nullable
        'educator_id',//nullable

        'study_year',
        'is_active',//default true

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
        'RosterStudents',

    ];

//    protected $mySearchableFields = [
//        'study_year',
//    ];

    //Attributes





    //Scopes
    public function scopeActive($query,$status=true){
        return $query->where('is_active',$status);
    }

    public function scopeActivate($query){
        return $query->update([
            'is_active' => true
        ]);
    }

    public function scopeUnActivate($query){
        return $query->update([
            'is_active' => false
        ]);
    }

    //Functions
    public function activate($status=true){
        return $this->update([
           'is_active' => $status
        ]);
    }


}
