<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;

class MissileHitStackWeightListener
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
