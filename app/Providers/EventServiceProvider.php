<?php

namespace App\Providers;

use App\AiV2\Events\MissileUpdated;
use App\AiV2\Listeners\MissileHitListener;
use App\AiV2\Listeners\MissileSunkListener;
use App\AiV2\Listeners\MissileSunkStackListener;
use App\AiV2\Listeners\MissileHitStackListener;
use App\AiV2\Listeners\MissileHitStackWeightListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MissileUpdated::class => [
            MissileHitStackListener::class,
            MissileHitStackWeightListener::class,
            MissileHitListener::class,
            MissileSunkListener::class,
            MissileSunkStackListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
