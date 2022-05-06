<?php

namespace App\AiV2\States;

use App\AiV2\Services\HeatmapService;
use App\AiV2\Vector;

class HuntingState extends ShipState
{
    public function shoot(HeatmapService $service): Vector
    {
        $heatmap = $service->createHeatmap();

        return Vector::from($heatmap->heaviestCoordinateWithParity());
    }
}
