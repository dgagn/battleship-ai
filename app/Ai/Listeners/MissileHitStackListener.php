<?php

namespace App\Ai\Listeners;

use App\Ai\Events\GameCreated;
use App\Ai\Events\MissileUpdated;
use App\Ai\Services\HeatmapService;
use App\Ai\Vector;
use App\Models\Game;

/**
 * Handles when a missile hit and manages the stack creation/
 * deletion/update.
 *
 * @author Dany Gagnon
 */
class MissileHitStackListener
{
    /**
     * Handle when a missile hit and manages the stack creation/
     * deletion/update.
     *
     * @param MissileUpdated $event the event of the missile
     * that was updated.
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
