<?php

namespace App\AiV2\Listeners;

use App\AiV2\Events\MissileUpdated;
use App\AiV2\Services\HeatmapService;
use App\AiV2\Vector;
use App\Models\Game;

class MissileHitStackListener
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
        /** @var Game $game */
        $game = $missile->game()->first();

        if ($missile->getResult() !== config('battleship.result.hit')) {
            return;
        }

        $heatmap = (new HeatmapService($game))->createHeatmap()
            ->generateAll();

        foreach (Vector::directions() as $direction) {
            $coordWithStackDirection = Vector::from($missile->getCoordinate())
                ->add($direction);

            if (! $coordWithStackDirection->within(0, config('battleship.size') - 1)) {
                continue;
            }

            $weightForStackCoord = $heatmap->get($coordWithStackDirection->str());
            if ($weightForStackCoord <= config('battleship.weighting.none')) {
                continue;
            }

            $stackCoordDirection = $direction->equals(Vector::up()) ||
                $direction->equals(Vector::down()) ?
                config('battleship.direction.vertical') :
                config('battleship.direction.horizontal');

            $game->stacks()->updateOrCreate([
                'coordinate' => $coordWithStackDirection->str(),
            ], [
                'weight' => $weightForStackCoord,
                'direction' => $stackCoordDirection,
            ]);
        }
    }
}
