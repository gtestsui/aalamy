<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\database\seeders\AdminSeeder;
use Modules\User\database\seeders\EducatorSeeder;
use Modules\User\database\seeders\LinkStudentToEducatorSeeder;
use Modules\User\database\seeders\LinkStudentToParentSeeder;
use Modules\User\database\seeders\LinkStudentToSchoolSeeder;
use Modules\User\database\seeders\ParentSeeder;
use Modules\User\database\seeders\SchoolSeeder;
use Modules\User\database\seeders\StudentSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //create superAdmin
        // $this->call(AdminSeeder::class);
        //create School
        $this->call(SchoolSeeder::class);
        //create Educator
        // $this->call(EducatorSeeder::class);
        // //create Student
        // $this->call(StudentSeeder::class);
        //create Parent
        // $this->call(ParentSeeder::class);
        // //create ParentStudent
        // $this->call(LinkStudentToParentSeeder::class);
        // //create SchoolStudent
        // $this->call(LinkStudentToSchoolSeeder::class);
        // //create EducatorStudent
        // $this->call(LinkStudentToEducatorSeeder::class);

    }
}
