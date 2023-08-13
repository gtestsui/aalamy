<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Controllers\Classes\Services\ConfirmationAccountServices;
use Modules\User\Http\Controllers\Classes\UserServices;
use Modules\User\Models\Educator;
use Modules\User\Models\ParentModel;
use Modules\User\Models\School;
use Modules\User\Models\Student;
use Modules\User\Models\User;

class ParentSeeder extends Seeder
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
            'fname' => 'parent',
            'lname' => 'l_parent',
            'email' => 'parent@gmail.com',
            'password' => '123456',
            'account_type' => 'parent',
            'account_id' => 'email',
            'verified_status' => 1,
            'unique_username' => UserServices::generateUniqueGuide(),
        ]);

        ParentModel::create([
            'user_id'=>$user->id,
        ]);
        DB::commit();
    }
}
