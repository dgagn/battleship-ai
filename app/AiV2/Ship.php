<?php

namespace App\AiV2;

use App\AiV2\Services\HeatmapService;
use App\AiV2\States\ShipState;

class Ship extends ShipState
{
    private ShipState $state;

    public function __construct(ShipState $state)
    {
        $this->state = $state;
    }

    public function setState(ShipState $state): void
    {
        $this->state = $state;
    }

    public function shoot(HeatmapService $service): Vector
    {
        return $this->state->shoot($service);
    }
}
