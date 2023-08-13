<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'fname' => 'admin',
            'lname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123456',
            'account_type' => 'superAdmin',
            'account_id' => 'email',
            'verified_status' => 1,
            'unique_username' => UserServices::generateUniqueGuide(),
        ]);
    }
}
