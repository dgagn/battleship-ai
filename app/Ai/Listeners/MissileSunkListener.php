<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;
use App\Ai\Services\HeatmapService;
use App\Ai\Vector;
use App\Models\Boat;
use App\Models\Game;

/**
 * Handles the hit when a missile sunk a ship.
 *
 * @author Dany Gagnon
 */
class MissileSunkListener
{
    /**
     * Handle the hit when a missile sunk a ship.
     *
     * @param MissileUpdated $event the event when a missile
     * updates
     */
    public function handle(MissileUpdated $event)
    {
        $missile = $event->getMissile();
        $game = $missile->game()->first();
        $ai = $game->ai()->first();

        if ($missile->getResult() <= config('battleship.result.hit')) {
            return;
        }

        $game->remainingBoats()
            ->where('boat_id', $missile->getResult() - 1)
            ->first()
            ->delete();

        $size = Boat::query()
            ->where('id', $missile->getResult() - 1)
            ->first()
            ->size - 1;

        $ai->update([
            'hits' => $ai->getHits() - $size,
        ]);
    }
}
