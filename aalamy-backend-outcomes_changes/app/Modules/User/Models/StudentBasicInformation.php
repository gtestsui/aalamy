<?php

namespace Modules\User\Models;


use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Orderable;
use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Model;

class StudentBasicInformation extends Model
{
    use DefaultGlobalScopes;

    use SoftDelete;
    use Searchable;
    use Orderable;
    use SoftDelete;


    public static function customizedBooted(){}


    protected $fillable = [
        'student_id',
        'father_fname',
        'mother_fname',
        'mother_lname',
        'grandfather_name',
        'place_of_birth',
        'place_of_birth_image',//مكان القيد
        'place_of_registration',//رقم القيد
        'number_of_registration',
        'religion',
        'passport_or_residence_card_number',
        'address',
        'residence_type',
        'residence_ownership',
        'distance_between_residence_and_school',
        'process_of_going_to_school',
        'telephone',
        'mobile',
        'curriculum_type',
        'sons_of_martyrs',
        'coming_from_school_name',//المدرسة القادم منها
        'student_situation',//وضع التلميذ
        'alhasakah_foreigners',
        'inclusion_of_people_with_disabilities',
        'muffled',
        'outstanding_test',
        'notes',
        'first_year',//مستجد او معيد
        'deleted',
        'deleted_by_cascade',
    ];

	protected $casts = [
        'alhasakah_foreigners' => 'boolean',
        'muffled' => 'boolean',
        'outstanding_test' => 'boolean',
        'first_year' => 'boolean',
        'sons_of_martyrs' => 'boolean',
        'inclusion_of_people_with_disabilities' => 'boolean',
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

	public function getPlaceOfBirthImageAttribute($key){
        if(isset($key))
            return baseRoute().$key;
        return $key;
    }



}
