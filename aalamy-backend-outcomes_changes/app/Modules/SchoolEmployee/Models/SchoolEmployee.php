<?php

namespace Modules\SchoolEmployee\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\Teacher;

class SchoolEmployee extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
    use SoftDelete;
    use Searchable;

    protected $table = 'school_employees';

    public static function customizedBooted(){}


    protected $fillable=[
        'school_id',
        'teacher_id',
        'fname',
        'lname',
        'father_name',
        'mother_name',
        'grandfather_name',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'original_state',
        'place_of_registration',
        'number_of_registration',
        'phone_number',
        'phone_code',
        'phone_iso_code',
        'nationality',
        'identifier_number',
        'address',
        'marriage_state',
        'job_info',
        'experience',
        'computer_skills',
        'added_manually_by_school',
        'type',



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

        'Certificates',

    ];


    /**
     * @var string[] $parentRelations
     * when the model belongs to another  parent model
     * and the model and his parent are deleted
     * andddd I can't restore the model if the parent is deleted
     * then I should fill $parentRelations array by
     * the relation name to that parent model
     * to prevent restore that model
     */
    protected $parentRelations = [

    ];

    private $mySearchableFields = [

    ];

    //Relations
    public function Teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function Certificates(){
        return $this->hasMany(SchoolEmployeeCertificate::class);
    }

    //Attributes



    //Scopes
    public function scopeMy($query,$school_id){
        return $query->where('school_id',$school_id);
    }

}
