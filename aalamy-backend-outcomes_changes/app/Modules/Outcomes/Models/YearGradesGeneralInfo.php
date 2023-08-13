<?php

namespace Modules\Outcomes\Models;

use App\Http\Traits\DefaultGlobalScopes;

use App\Http\Traits\Searchable;
use App\Http\Traits\SoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Level\Models\BaseLevel;
use Modules\Level\Models\BaseSubject;
use Modules\Outcomes\Traits\ModelRelations\StudentStudyingInformationRelations;

class YearGradesGeneralInfo extends Model
{
    use DefaultGlobalScopes;
    use HasFactory;
//    use SoftDelete;
    use Searchable;
//    use StudentStudyingInformationRelations;

    protected $table = 'year_grades_general_info';

    public static function customizedBooted(){}


    /**
     * @var string[] $relationsSoftDelete
     * its contain our relations name but not all relations
     * just the relations we want it to delete by cascade while using softDelete
     */
    protected $relationsSoftDelete = [

    ];

    protected $fillable=[
        'student_studying_information_id',
        'school_manager_notes',
        'school_manager_notes_semester_1',
        'school_manager_notes_semester_2',
        'school_manager_notes',
        'parent_notes',
        //جدول دوام التلميذ
        'actual_attendee_hours_semester_1',//الدوام الفعلي الفصل الاول
        'actual_attendee_hours_semester_2',//الدوام الفعلي الفصل الثاني
        'student_attendee_hours_semester_1',//الدوام الفعلي الفصل الاول
        'student_attendee_hours_semester_2',//الدوام الفعلي الفصل الثاني
        'excused_absence_semester_1',//الغياب المبرر الفصل الاول
        'excused_absence_semester_2',//الغياب المبرر الفصل الثاني
        'unexcused_absence_semester_1',//الغياب الغير مبرر الفصل الاول
        'unexcused_absence_semester_2',//الغياب الغير مبرر الفصل الثاني
        //التوحيهات التربوية للمعلم
        'instructional_directions_for_the_teacher',//التوجيهات التربوية للمعلم
        //نتيجة التلميذ
        'class_success',//نجاح الى الصف
        'failing_class',//رسوب في الصف
        'transfer_to_class_because_he_is_repeater',//نقل الى الصف لانه معيد
        'transfer_to_class_because_exhaust_the_years_of_failure',//نقل الى الصف لاستنفاذ سنوات الرسوب

        'directorate_of_education_in_the_province_of',//مديرية التربية في المحافظة
        'school',//المدرسة
        'serial_number',//الرقم المتسلسل
        'student_name',//اسم التلميذ
        'the_father',//اسم الاب
        'the_mother',//اسم الام
        'year_of_date_of_birth',//سنة الميلاد
        'month_of_date_of_birth',//شهر الميلاد
        'day_of_date_of_birth',//يوم الميلاد
        'date_of_birth',//الميلاد
        'year',//العام
        'class',//الصف
        'division',//الشعبة
        'foreign_language',//اللغة الاجنبية
        'the_number_is_in_the_public_record',//الرقم في السجل العام
        'manager_name',//اسم المدير
        'teacher_name',//اسم الاستاذ
        'start_educational_year',//
        'end_educational_year',//
        'manager_notes_semester_1',//ملاحظات الادارة فصل اول
        'manager_notes_semester_2',//ملاحظات الادارة فصل تاني
        'day_of_manager_signature_date1',
        'month_of_manager_signature_date1',
        'year_of_manager_signature_date1',
        'day_of_manager_signature_date2',
        'month_of_manager_signature_date2',
        'year_of_manager_signature_date2',
        'hijri_year1',
        'hijri_year2',

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


    public function StudentStudyingInformation(){
        return $this->belongsTo(StudentStudyingInformation::class);
    }

    //Attributes



    //Scopes

}
