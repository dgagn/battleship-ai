<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Services\HeatmapService;
use App\Ai\Vector;
use App\Models\Boat;
use App\Models\Game;

/**
 * Handles creating the AI and the remaining boats when a game
 * is created.
 *
 * @author Dany Gagnon
 */
class GameCreatedListener
{
    /**
     * Handles creating the AI and the remaining boats when a
     * game is created.
     *
     * @param GameCreated $event the event when a game is created
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
