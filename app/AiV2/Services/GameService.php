<?php

namespace App\AiV2\Services;

use App\AiV2\Vector;
use App\Models\Ai;
use App\Models\Boat;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Creates a board with a unique set of placements. It makes
 * sure to place the boats on the less weighted coordinates to
 * make sure algorithms that check probabilities is less efficient
 * against this placement. It also makes sure to select at random
 * (usually corners) the least probable spot. However, an algorithm
 * that targets corners is very strong against mine (I would
 * say a counter) because my boats gets placed in the corners
 * a lot).
 *
 * @author Dany Gagnon
 */
class GameService
{
    /**
     * @var Game the game to associate the board with
     */
    private Game $partie;

    /**
     * Constructs a game service that is responsible to create
     * the board related stuff.
     *
     * @param Game $partie the game to create a board for
     */
    public function __construct(Game $partie)
    {
        $this->partie = $partie;
    }

    public function createGameSetup()
    {
        Boat::all()->each(fn ($boat) => $this->partie->remainingBoats()->create([
            'boat_id' => $boat->id,
        ]));
        Ai::query()->create([
            'game_id' => $this->partie->id,
        ]);
    }
}
