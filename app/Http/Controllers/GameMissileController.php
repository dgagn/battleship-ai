<?php

namespace App\Http\Controllers;

use App\Ai\Events\MissileUpdated;
use App\Ai\Services\HeatmapService;
use App\Ai\Services\MissileService;
use App\Http\Requests\UpdateGameMissileRequest;
use App\Http\Resources\GameMissileResource;
use App\Models\Game;
use App\Models\Missile;
use Illuminate\Auth\Access\AuthorizationException;

class GameMissileController extends Controller
{
    /**
     * Creates an AI based missile.
     *
     * @throws AuthorizationException
     */
    public function store(Game $game): GameMissileResource
    {
        $this->authorize('view', $game);
        $service = new MissileService($game);

        return new GameMissileResource($service->createAiShot());
    }

    /**
     * Update the result of the missile.
     *
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

        return new GameMissileResource($missile);
    }

    public function show(Game $game)
    {
        return (new HeatmapService($game))->createHeatmap()
            ->generateWithStacks();
    }
}
