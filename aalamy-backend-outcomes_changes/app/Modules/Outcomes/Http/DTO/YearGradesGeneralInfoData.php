<?php


namespace Modules\Outcomes\Http\DTO;


use App\Http\Controllers\DTO\Parents\ObjectData;
use Illuminate\Http\Request;

final class YearGradesGeneralInfoData extends ObjectData
{
    public ?int $id = null;
    public ?string $school_manager_notes;
    public ?string $school_manager_notes_semester_1;
    public ?string $school_manager_notes_semester_2;
    public ?string $parent_notes;
    public ?string $actual_attendee_hours_semester_1;
    public ?string $actual_attendee_hours_semester_2;
    public ?string $student_attendee_hours_semester_1;
    public ?string $student_attendee_hours_semester_2;
    public ?string $excused_absence_semester_1;
    public ?string $excused_absence_semester_2;
    public ?string $unexcused_absence_semester_1;
    public ?string $unexcused_absence_semester_2;
    public ?string $instructional_directions_for_the_teacher;
    public ?string $class_success;
    public ?string $failing_class;
    public ?string $transfer_to_class_because_he_is_repeater;
    public ?string $transfer_to_class_because_exhaust_the_years_of_failure;

    public ?string $directorate_of_education_in_the_province_of;
    public ?string $school;
    public ?string $serial_number;
    public ?string $student_name;
    public ?string $the_father;
    public ?string $the_mother;
    public ?string $year_of_date_of_birth;
    public ?string $month_of_date_of_birth;
    public ?string $day_of_date_of_birth;
    public ?string $date_of_birth;
    public ?string $year;
    public ?string $class;
    public ?string $division;
    public ?string $foreign_language;
    public ?string $the_number_is_in_the_public_record;
    public ?string $manager_name;
    public ?string $teacher_name;
    public ?string $start_educational_year;
    public ?string $end_educational_year;
    public ?string $manager_notes_semester_1;
    public ?string $manager_notes_semester_2;
    public ?string $day_of_manager_signature_date1;
    public ?string $month_of_manager_signature_date1;
    public ?string $year_of_manager_signature_date1;
    public ?string $day_of_manager_signature_date2;
    public ?string $month_of_manager_signature_date2;
    public ?string $year_of_manager_signature_date2;
    public ?string $hijri_year1;
    public ?string $hijri_year2;


    public static function fromRequest(Request $request): self
    {

        return new self([
            'school_manager_notes' => self::getParameterValue($request, 'school_manager_notes'),
            'school_manager_notes_semester_1' => self::getParameterValue($request, 'school_manager_notes_semester_1'),
            'school_manager_notes_semester_2' => self::getParameterValue($request, 'school_manager_notes_semester_2'),
            'parent_notes' => self::getParameterValue($request, 'parent_notes'),
            'actual_attendee_hours_semester_1' => self::getParameterValue($request, 'actual_attendee_hours_semester_1'),
            'actual_attendee_hours_semester_2' => self::getParameterValue($request, 'actual_attendee_hours_semester_2'),
            'student_attendee_hours_semester_1' => self::getParameterValue($request, 'student_attendee_hours_semester_1'),
            'student_attendee_hours_semester_2' => self::getParameterValue($request, 'student_attendee_hours_semester_2'),
            'excused_absence_semester_1' => self::getParameterValue($request, 'excused_absence_semester_1'),
            'excused_absence_semester_2' => self::getParameterValue($request, 'excused_absence_semester_2'),
            'unexcused_absence_semester_1' => self::getParameterValue($request, 'unexcused_absence_semester_1'),
            'unexcused_absence_semester_2' => self::getParameterValue($request, 'unexcused_absence_semester_2'),
            'instructional_directions_for_the_teacher' => self::getParameterValue($request, 'instructional_directions_for_the_teacher'),
            'class_success' => self::getParameterValue($request, 'class_success'),
            'failing_class' => self::getParameterValue($request, 'failing_class'),
            'transfer_to_class_because_he_is_repeater' => self::getParameterValue($request, 'transfer_to_class_because_he_is_repeater'),
            'transfer_to_class_because_exhaust_the_years_of_failure' => self::getParameterValue($request, 'transfer_to_class_because_exhaust_the_years_of_failure'),
            'directorate_of_education_in_the_province_of' => self::getParameterValue($request, 'directorate_of_education_in_the_province_of'),
            'school' => self::getParameterValue($request, 'school'),
            'serial_number' => self::getParameterValue($request, 'serial_number'),
            'student_name' => self::getParameterValue($request, 'student_name'),
            'the_father' => self::getParameterValue($request, 'the_father'),
            'the_mother' => self::getParameterValue($request, 'the_mother'),
            'year_of_date_of_birth' => self::getParameterValue($request, 'year_of_date_of_birth'),
            'month_of_date_of_birth' => self::getParameterValue($request, 'month_of_date_of_birth'),
            'day_of_date_of_birth' => self::getParameterValue($request, 'day_of_date_of_birth'),
            'date_of_birth' => self::getParameterValue($request, 'date_of_birth'),
            'year' => self::getParameterValue($request, 'year'),
            'class' => self::getParameterValue($request, 'class'),
            'division' => self::getParameterValue($request, 'division'),
            'foreign_language' => self::getParameterValue($request, 'foreign_language'),
            'the_number_is_in_the_public_record' => self::getParameterValue($request, 'the_number_is_in_the_public_record'),
            'manager_name' => self::getParameterValue($request, 'manager_name'),
            'teacher_name' => self::getParameterValue($request, 'teacher_name'),
            'start_educational_year' => self::getParameterValue($request, 'start_educational_year'),
            'end_educational_year' => self::getParameterValue($request, 'end_educational_year'),
            'manager_notes_semester_1' => self::getParameterValue($request, 'manager_notes_semester_1'),
            'manager_notes_semester_2' => self::getParameterValue($request, 'manager_notes_semester_2'),
            'day_of_manager_signature_date1' => self::getParameterValue($request, 'day_of_manager_signature_date1'),
            'month_of_manager_signature_date1' => self::getParameterValue($request, 'month_of_manager_signature_date1'),
            'year_of_manager_signature_date1' => self::getParameterValue($request, 'year_of_manager_signature_date1'),
            'day_of_manager_signature_date2' => self::getParameterValue($request, 'day_of_manager_signature_date2'),
            'month_of_manager_signature_date2' => self::getParameterValue($request, 'month_of_manager_signature_date2'),
            'year_of_manager_signature_date2' => self::getParameterValue($request, 'year_of_manager_signature_date2'),
            'hijri_year1' => self::getParameterValue($request, 'hijri_year1'),
            'hijri_year2' => self::getParameterValue($request, 'hijri_year2'),


        ]);
    }


    /**
     * @param Request $request
     * @param string $parameterName the parameter name which is sent in the request
     *
     * check if the parameter is found in the request and its value is empty string (when the user trying to delete the inserted value)
     *
     * @return mixed|string|null
     */
    private static function getParameterValue($request, $parameterName)
    {
        return $request->has($parameterName) && !isset($request->{$parameterName})
            ? ''
            : (
            isset($request->{$parameterName})
                ? $request->{$parameterName}
                : null
            );
    }

}
