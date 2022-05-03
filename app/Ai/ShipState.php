<?php

namespace App\Ai;

use App\Models\Partie;

abstract class ShipState
{
    abstract public function shoot(ShipAi $ship, Partie $partie): Vector;
}
