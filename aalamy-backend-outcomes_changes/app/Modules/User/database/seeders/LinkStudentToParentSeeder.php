<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\ParentModel;
use Modules\User\Models\ParentStudent;
use Modules\User\Models\Student;

class LinkStudentToParentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::beginTransaction();
        $student = Student::firstOrFail();
        $parent = ParentModel::firstOrFail();
        $parentStudent = ParentStudent::where('student_id',$student->id)
            ->where('parent_id',$parent->id)
            ->first();
        if(is_null($parentStudent))
            ParentStudent::create([
               'parent_id' => $parent->id,
               'student_id' => $student->id,
            ]);

        DB::commit();
    }
}
