<?php

namespace App\Providers;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;
use App\Ai\Listeners\GameCreatedListener;
use App\Ai\Listeners\MissileHitListener;
use App\Ai\Listeners\MissileHitStackListener;
use App\Ai\Listeners\MissileHitStackWeightListener;
use App\Ai\Listeners\MissileSunkListener;
use App\Ai\Listeners\MissileSunkStackListener;
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
        GameCreated::class => [
            GameCreatedListener::class,
        ],
        MissileUpdated::class => [
            MissileHitStackListener::class,
            MissileHitStackWeightListener::class,
            MissileHitListener::class,
            MissileSunkListener::class,
            MissileSunkStackListener::class,
        ],
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
