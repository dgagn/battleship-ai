<?php

namespace App\Ai\Providers;

use App\Ai\ShipAi;
use App\Ai\States\HuntingState;
use App\Ai\States\PassiveState;
use App\Models\Missile;
use Illuminate\Support\ServiceProvider;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Ai', function () {
            return new ShipAi(new HuntingState());
        });
    }
}
