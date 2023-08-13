<?php

namespace Modules\User\database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;

class LinkStudentToSchoolSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::beginTransaction();
        $student = Student::firstOrFail();
        $school = School::firstOrFail();
        $schoolStudent = SchoolStudent::where('student_id',$student->id)
            ->where('school_id',$school->id)
            ->active()
            ->first();
        if(is_null($schoolStudent))
            SchoolStudent::create([
               'school_id' => $school->id,
               'student_id' => $student->id,
               'start_date' => Carbon::now(),
            ]);

        DB::commit();
    }
}
