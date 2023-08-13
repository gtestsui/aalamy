<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;

class StudentFamilyInformation extends Model
{
    use DefaultGlobalScopes;

    use SoftDelete;
    use Searchable;
    use Orderable;
    use SoftDelete;


    public static function customizedBooted(){}


    protected $fillable = [
        'student_id',
        'father_work',
        'father_phone',
        'mother_living_with_father',
        'mother_work',
        'mother_phone',
        'father_studying',
        'mother_studying',
        'family_income',
        'father_and_mother_are_relatives',
        'older_brothers_count',
        'younger_brothers_count',
        'older_sisters_count',
        'younger_sisters_count',
        'have_uncle_from_father',
        'have_uncle_from_mother',
        'living_in_same_house',
        'have_internet_in_the_house',
        'workers_from_the_family_count',
        'deleted',
        'deleted_by_cascade',
        'deleted_at',
    ];


	protected $casts = [
        'mother_living_with_father' => 'boolean',
        'have_internet_in_the_house' => 'boolean',
        'have_uncle_from_father' =>'boolean' ,
        'have_uncle_from_mother' => 'boolean',
        'father_and_mother_are_relatives' => 'boolean',
        'living_in_same_house' => 'boolean',
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
