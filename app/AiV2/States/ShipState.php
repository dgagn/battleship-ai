<?php

namespace App\AiV2\States;

use App\AiV2\Services\HeatmapService;
use App\AiV2\Vector;

abstract class ShipState
{
    abstract public function shoot(HeatmapService $service): Vector;
}
