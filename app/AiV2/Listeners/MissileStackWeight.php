<?php

namespace App\AiV2\Listeners;

use App\AiV2\Events\MissileUpdated;

class MissileStackWeight
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

        $hit = config('battleship.result.hit');

        if ($missile->getResult() !== $hit) {
            return;
        }

        if ($ai->isTargetMode()) {
            $stackOfMissileCoordinate = $game->stacks()->withTrashed()->where('coordinate', $missile->getCoordinate());
            if (!$stackOfMissileCoordinate->exists()) {
                return;
            }
            $game->stacks()->where('direction', $stackOfMissileCoordinate->first()->getDirection())->get()
                ->each(function($stack) {
                $stack->update([
                    'weight' => $stack->getWeight() + config('battleship.weighting.direction')
                ]);
            });
        }
    }
}
