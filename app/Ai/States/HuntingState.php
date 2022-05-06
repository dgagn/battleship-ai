<?php

namespace App\Ai\States;

use App\Ai\Services\HeatmapService;
use App\Ai\Vector;

/**
 * Hunting state represents a ship state. It is the default
 * state for searching all the ships. It is a smart search
 * because it makes a probability density function and takes
 * the most probable spot a boat could be. Furthermore, it also
 * takes into account parity. For exemple, if the smallest boat
 * is sunk, the algorithm will shoot every third square instead
 * of every 2 because all the boats can be shot at least once
 * with being shot every 3 square.
 *
 * @author Dany Gagnon
 */
class HuntingState extends ShipState
{
    /**
     * Returns the next coordinate for the shot. It creates
     * a heatmap with a parity of the smallest boat.
     *
     * @param HeatmapService $service the heatmap service gives
     * us all the information for creating a heatmap
     * @return Vector the next coordinate for the shot.
     */
    public function shoot(HeatmapService $service): Vector
    {
        $heatmap = $service->createHeatmap();

        return Vector::from($heatmap->heaviestCoordinateWithParity());
    }
}
