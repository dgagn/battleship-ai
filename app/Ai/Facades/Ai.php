<?php

namespace App\Ai\Facades;

use App\Ai\ShipState;
use App\Models\Game;
use Illuminate\Support\Facades\Facade;

/**
 * @method static shoot(Game $partie)
 * @method static setState(ShipState $state)
 * @method static hunting()
 * @method static passive()
 * @method static getState()
 * @method static target()
 */
class Ai extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Ai';
    }
}
