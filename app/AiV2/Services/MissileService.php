<?php

namespace App\AiV2\Services;

use App\AiV2\Ship;
use App\AiV2\States\HuntingState;
use App\AiV2\States\TargetState;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

class MissileService
{
    private Game $partie;

    public function __construct(Game $partie)
    {
        $this->partie = $partie;
    }

    public function createAiShot(): Model
    {
        $ai = $this->partie->ai()->first();
        $ship = new Ship(
            $ai->isTargetMode() ?
            new TargetState() :
            new HuntingState()
        );
        $bestShotVector = $ship->shoot(new HeatmapService($this->partie));

        return $this->partie->missiles()->create([
            'coordinate' => $bestShotVector->str(),
        ]);
    }
}
