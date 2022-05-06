<?php

namespace App\Ai;

use App\Ai\Services\HeatmapService;
use App\Ai\States\ShipState;

/**
 * The ship class follows the state design pattern, so I can
 * have different state. For exemple, the hunting state of the
 * boat is when there's no hit on the stacks. If hits are seen
 * in the AI, the state changes to the target state. This class
 * is responsible to create a bridge and prevent creating a
 * lot of `if` cases.
 *
 * @author Dany Gagnon
 */
class Ship extends ShipState
{
    /** @var ShipState the current state of the ship. */
    private ShipState $state;

    /**
     * Constructs the ship with a given default state.
     *
     * @param ShipState $state the state of the ship to create
     */
    public function __construct(ShipState $state)
    {
        $this->state = $state;
    }

    /**
     * Sets the internal state of the ship to another one.
     *
     * @param ShipState $state the new state to assign to the
     * ship
     */
    public function setState(ShipState $state): void
    {
        $this->state = $state;
    }

    /**
     * Generate a shooting vector for the next coordinate. It
     * is different depending on its internal state. It calls
     * shoot on the state.
     *
     * @param HeatmapService $service tbe heatmap service to be
     * able to create heatmaps with all the information a heatmap
     * needs
     * @return Vector the coordinate of the shot
     */
    public function shoot(HeatmapService $service): Vector
    {
        return $this->state->shoot($service);
    }
}
