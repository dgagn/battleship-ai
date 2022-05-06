<?php

namespace App\Ai;

use App\Models\Game;

abstract class ShipState
{
    abstract public function shoot(ShipAi $ship, Game $partie): Vector;
}
