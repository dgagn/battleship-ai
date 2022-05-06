<?php

namespace App\Ai\States;

use App\Ai\Services\HeatmapService;
use App\Ai\Vector;

/**
 * A ship state belongs to the ship. It's a class following
 * the state pattern.
 *
 * @author Dany Gagnon
 */
abstract class ShipState
{
    /**
     * Returns a coordinate for the next best position.
     *
     * @param HeatmapService $service the service for creating
     * a heatmap
     * @return Vector a coordinate for the next best position.
     */
    abstract public function shoot(HeatmapService $service): Vector;
}
