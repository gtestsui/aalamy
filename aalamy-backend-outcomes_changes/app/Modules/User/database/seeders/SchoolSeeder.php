<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class SchoolSeeder extends Seeder
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
        $user = User::create([
            'fname' => 'school',
            'lname' => 'l_school',
            'email' => 'school@gmail.com',
            'password' => '123456',
            'account_type' => 'school',
            'account_id' => 'email',
            'verified_status' => 1,
            'unique_username' => UserServices::generateUniqueGuide(),
        ]);

        School::create([
            'user_id'=>$user->id,
            'school_name' => 'al-hadara school',
            'bio' => 'this is my bio',
        ]);

        DB::commit();
    }
}
