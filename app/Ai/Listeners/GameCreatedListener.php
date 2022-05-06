<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Services\HeatmapService;
use App\Ai\Vector;
use App\Models\Boat;
use App\Models\Game;

class GameCreatedListener
{
    /**
     * Handle the event.
     *
     * @param GameCreated $event
     * @return void
     */
    public function handle(GameCreated $event)
    {
        $game = $event->getGame();
        Boat::all()->each(fn ($boat) => $game->remainingBoats()->create([
            'boat_id' => $boat->id,
        ]));
        $game->ai()->create();
    }
}
