<?php

namespace App\Providers;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Gate::define('test', function (User $user, Gallery $gallery) {
//            if($gallery->type == 'photos')
//                if($user->id == 2)
//                    return true;
//        });

        Passport::routes();


//        Passport::tokensCan([
//            'user' => 'User Type',
//            'superAdmin' => 'Admin User Type',
//        ]);
    }
}
