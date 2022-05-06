<?php

namespace App\Ai\States;

use App\Ai\Services\HeatmapService;
use App\Ai\Vector;

/**
 * The target state represent when the ship has hit another and
 * tries to nuke the ship on the board. It selects a target and
 * adds weighting of the stack to the heatmap to be able to
 * attack all the enemies based on the modified heatmap.
 *
 * @author Dany Gagnon
 */
class TargetState extends ShipState
{
    /**
     * Returns the next coordinate for the shot. It creates
     * a heatmap with the stacks.
     *
     * @param HeatmapService $service the heatmap service gives
     * us all the information for creating a heatmap
     * @return Vector the next coordinate for the shot. It creates
     * a heatmap with the stacks.
     */
    public function shoot(HeatmapService $service): Vector
    {
        $heatmap = $service->createHeatmap();

        return Vector::from($heatmap->heaviestCoordinateWithStacks());
    }
}
