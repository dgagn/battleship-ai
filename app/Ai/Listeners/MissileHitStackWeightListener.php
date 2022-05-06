<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;

/**
 * Handles when a missile hits a boat and manages the stack
 * weight.
 *
 * @author Dany Gagnon
 */
class MissileHitStackWeightListener
{
    /**
     * Handle when a missile hits a boat and manages the stack
     * weight.
     *
     * @param MissileUpdated $event the event to handle when a
     * missile updates
     */
    public function handle(MissileUpdated $event)
    {
        $missile = $event->getMissile();
        $game = $missile->game()->first();
        $ai = $game->ai()->first();

        if ($missile->getResult() !== config('battleship.result.hit')) {
            return;
        }

        if ($ai->isTargetMode()) {
            $stackOfMissileCoordinate = $game->stacks()->withTrashed()->where('coordinate', $missile->getCoordinate());
            if (! $stackOfMissileCoordinate->exists()) {
                return;
            }
            $game->stacks()->where('direction', $stackOfMissileCoordinate->first()->getDirection())->get()
                ->each(function ($stack) {
                    $stack->update([
                        'weight' => $stack->getWeight() + config('battleship.weighting.direction'),
                    ]);
                });
        }
    }
}
