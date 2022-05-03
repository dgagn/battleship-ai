<?php

namespace App\Providers;

use App\Ai\Observers\MissileObserver;
use App\Ai\ShipAi;
use App\Ai\States\PassiveState;
use App\Models\Missile;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
