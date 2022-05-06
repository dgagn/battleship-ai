<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;
use App\Ai\Services\HeatmapService;
use App\Ai\Vector;
use App\Models\Boat;
use App\Models\Game;

/**
 * Class responsible to handle the sunk event for the stack.
 *
 * @author Dany Gagnon
 */
class MissileSunkStackListener
{
    /**
     * Handle when a missile updates, it is responsible to
     * handle the stack when a missile sunk a boat.
     *
     * @param MissileUpdated $event the missile updated event
     */
    public function handle(MissileUpdated $event)
    {
        $missile = $event->getMissile();
        $game = $missile->game()->first();
        $ai = $game->ai()->first();

        if ($missile->getResult() <= config('battleship.result.hit')) {
            return;
        }

        if ($ai->isHuntMode()) {
            $game->stacks()->each(function ($stack) use ($missile) {
                if ($stack->coordinate != $missile->getCoordinate()) {
                    $stack->delete();
                }
            });
        }
    }
}
