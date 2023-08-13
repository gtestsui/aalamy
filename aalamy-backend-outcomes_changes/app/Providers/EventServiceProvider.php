<?php

namespace App\Providers;

use App\Modules\User\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\SchoolInvitation\Models\SchoolTeacherRequest;
use Modules\SchoolInvitation\Observers\SchoolTeacherRequestObserver;
use Modules\User\Models\User;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    
    	\SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\\Apple\\AppleExtendSocialite@handle',
        ],
    
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
//        User::observe(UserObserver::class);
//        SchoolTeacherRequest::observe(SchoolTeacherRequestObserver::class);
    }
}
