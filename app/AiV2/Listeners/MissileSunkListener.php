<?php

namespace App\AiV2\Listeners;

use App\AiV2\Events\MissileUpdated;
use App\AiV2\Services\HeatmapService;
use App\AiV2\Vector;
use App\Models\Boat;
use App\Models\Game;

class MissileSunkListener
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
