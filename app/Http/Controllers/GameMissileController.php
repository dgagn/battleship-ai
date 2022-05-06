<?php

namespace App\Http\Controllers;

use App\Ai\Direction;
use App\Ai\Grid;
use App\Ai\Vector;
use App\AiV2\Services\HeatmapService;
use App\AiV2\Services\MissileService;
use App\AiV2\Events\MissileUpdated;
use App\Http\Requests\UpdateGameMissileRequest;
use App\Http\Resources\GameMissileResource;
use App\Models\Boat;
use App\Models\Game;
use App\Models\Missile;
use Illuminate\Auth\Access\AuthorizationException;

class GameMissileController extends Controller
{
    public function store(Game $game): GameMissileResource
    {
        $this->authorize('view', $game);
        $service = new MissileService($game);

        return new GameMissileResource($service->createAiShot());
    }

    /**
     * @param UpdateGameMissileRequest $request
     * @param Game $game
     * @param Missile $missile
     * @return GameMissileResource
     * @throws AuthorizationException
     */
    public function update(UpdateGameMissileRequest $request, Game $game, Missile $missile): GameMissileResource
    {
        $this->authorize('view', $game);
        $result = (int) $request->validated('resultat');
        $missile->update([
            'result' => $result,
        ]);

        event(new MissileUpdated($missile));

        $coord = $missile->coordinate;

        $ai = $game->ai()->first();
        if ($result === 1) {
            $directions = Vector::directions();
            $vec = Vector::make($coord);

            $sizes = $game->remainingBoats()->get()
                ->map(fn ($boat) => Boat::query()->where('id', $boat->boat_id)->first()->size);
            $shots = $game->missiles()->get()
                ->map(fn ($missile) => $missile->coordinate);
            $grid = new Grid($sizes, $shots);

            $heatmap = $grid->heatmaps();

            foreach ($directions as $direction) {
                $newvec = $vec->add($direction);
                if (! $newvec->within(0, 9)) {
                    continue;
                }

                $heat = $heatmap->get(strval($newvec));

                if ($heat === 0) {
                    continue;
                }

                $dir = $direction == Vector::up() ||
                $direction == Vector::down() ? Direction::Y
                    : Direction::X;

                $game->stacks()->updateOrCreate([
                    'coordinate' => strval($newvec),
                ], [
                    'weight' => $heat,
                    'direction' => $dir,
                ]);
            }

            if ($ai->isTargetMode()) {
                $stackers = $game->stacks()->withTrashed()->where('coordinate', $coord);
                if ($stackers->exists()) {
                    $stacksOfDirection = $game->stacks()->where('direction', $stackers->first()->direction)->get();
                    foreach ($stacksOfDirection as $sod) {
                        $sod->weight += 100;
                        $sod->save();
                    }
                }
            }

            $ai->hits++;
            $ai->save();
        } elseif ($result > 1) {
            $game->remainingBoats()
                ->where('boat_id', $result - 1)
                ->first()
                ->delete();
            $size = Boat::query()
                ->where('id', $result - 1)
                ->first()
                ->size;

            $ai->hits -= $size - 1;
            $ai->save();

            if ($ai->hits == 0) {
                $ai->save();
                $game->stacks()->each(function ($stack) use ($coord) {
                    if ($stack->coordinate != $coord) {
                        $stack->delete();
                    }
                });
            }
        }

        return new GameMissileResource($missile);
    }

    public function show(Game $game)
    {
        return (new HeatmapService($game))->createHeatmap()
            ->generateWithStacks();
    }
}
