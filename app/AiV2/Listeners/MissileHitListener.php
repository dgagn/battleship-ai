<?php

namespace App\AiV2\Listeners;

use App\AiV2\Events\MissileUpdated;
use App\AiV2\Services\HeatmapService;
use App\AiV2\Vector;
use App\Models\Game;

class MissileHitListener
{
    /**
     * Handle the event.
     *
     * @param MissileUpdated $event
     * @return void
     */
    public function handle(MissileUpdated $event)
    {
        $missile = $event->getMissile();
        $game = $missile->game()->first();
        $ai = $game->ai()->first();

        if ($missile->getResult() !== config('battleship.result.hit')) {
            return;
        }

        $ai->update([
            'hits' => $ai->getHits() + 1
        ]);
    }
}
