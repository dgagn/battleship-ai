<?php

namespace App\Http\Controllers;

use App\Ai\Events\GameCreated;
use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * The game controller controls the routes related to creating
 * or deleting a battleship game.
 *
 * @author Dany Gagnon
 */
class GameController extends Controller
{
    /**
     * Store a game as well as all the setup this game needs
     * like the AI and the remaining boats.
     *
     * @param GameRequest $request the request of the new
     * game
     * @return GameResource a new game resource that creates
     * a board and a new game
     */
    public function store(GameRequest $request): GameResource
    {
        /** @var Game $game */
        $game = Game::query()->create([
            'opponent' => $request->validated('adversaire'),
        ]);

        event(new GameCreated($game));

        return new GameResource($game);
    }

    /**
     * Destroys the specified game and all the things in the
     * database will cascade like magic.
     *
     * @param Game $game the game to destroy
     * @return GameResource the resource of the game
     * @throws AuthorizationException can throw an exception
     * if the user is not authorized to delete this game
     */
    public function destroy(Game $game): GameResource
    {
        $this->authorize('delete', $game);
        $game->delete();

        return new GameResource($game);
    }
}
