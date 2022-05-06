<?php

namespace App\Ai;

use App\Ai\States\HuntingState;
use App\Ai\States\TargetState;
use App\Models\Game;

class ShipAi
{
    private ShipState $state;

    public function __construct(ShipState $state)
    {
        $this->state = $state;
    }

    public function hunting(): void
    {
        $this->state = new HuntingState();
    }

    public function target(): void
    {
        $this->state = new TargetState();
    }

    public function shoot(Game $partie): Vector
    {
        return $this->state->shoot($this, $partie);
    }

    public function setState(ShipState $state): void
    {
        $this->state = $state;
    }
}
