<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;
use App\Ai\Services\HeatmapService;
use App\Ai\Vector;
use App\Models\Game;

/**
 * Handles updating the hits when a missile hits a boat.
 *
 * @author Dany Gagnon
 */
class MissileHitListener
{
    /**
     * Handles updating the hits when a missile hits a boat.
     *
     * @param MissileUpdated $event the event when a missile
     * is updated
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
            'hits' => $ai->getHits() + 1,
        ]);
    }
}
