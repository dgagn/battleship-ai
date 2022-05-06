<?php

namespace App\Ai\Services;

use App\Ai\Ship;
use App\Ai\States\HuntingState;
use App\Ai\States\TargetState;
use App\Models\Game;
use App\Models\Missile;
use Illuminate\Database\Eloquent\Model;

/**
 * The service that is responsible to create a missile with
 * the best possible coordinate.
 *
 * @author Dany Gagnon
 */
class MissileService
{
    /** @var Game the game associated with the missile. */
    private Game $game;

    /**
     * Constructs a missile service that is responsible to
     * create the best missile possible for a given situation.
     *
     * @param Game $game the game associated with the missile.
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Returns a new missile with the best coordinate based
     * on the AI.
     *
     * @return Missile a new missile with the best coordinate based
     * on the AI.
     */
    public function createAiShot(): Model
    {
        $ai = $this->game->ai()->first();
        $ship = new Ship(
            $ai->isTargetMode() ?
            new TargetState() :
            new HuntingState()
        );
        $bestShotVector = $ship->shoot(new HeatmapService($this->game));

        return $this->game->missiles()->create([
            'coordinate' => $bestShotVector->str(),
        ]);
    }
}
