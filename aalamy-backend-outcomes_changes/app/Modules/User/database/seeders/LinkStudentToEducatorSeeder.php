<?php

namespace Modules\User\database\seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\Educator;
use Modules\User\Models\EducatorStudent;
use Modules\User\Models\School;
use Modules\User\Models\SchoolStudent;
use Modules\User\Models\Student;

class LinkStudentToEducatorSeeder extends Seeder
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
        $educator = Educator::firstOrFail();
        $educatorStudent = EducatorStudent::where('student_id',$student->id)
            ->where('educator_id',$educator->id)
            ->active()
            ->first();
        if(is_null($educatorStudent))
            EducatorStudent::create([
               'educator_id' => $educator->id,
               'student_id' => $student->id,
               'start_date' => Carbon::now(),
            ]);

        DB::commit();
    }
}
