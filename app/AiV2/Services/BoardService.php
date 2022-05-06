<?php

namespace App\AiV2\Services;

use App\AiV2\Vector;
use App\Models\Ai;
use App\Models\Boat;
use App\Models\Game;
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
class BoardService
{
    /**
     * @var Game the game to associate the board with
     */
    private Game $partie;

    /**
     * Constructs a board service that is responsible to create
     * the board related stuff.
     *
     * @param Game $partie the game to create a board for
     */
    public function __construct(Game $partie)
    {
        $this->partie = $partie;
    }

    /**
     * Returns a board with a unique collection of board placements.
     * It makes sure to select the less probable location to
     * place the boats. It creates a heatmap and selects the
     * last X element from it. Only from there it does a random
     * to make sure to randomize the chances of the boats to
     * make sure an algorithm doesn't shoot at the same places
     * everytime and win. It favors corners, because boats
     * usually don't have a lot of places there.
     *
     * @return Collection a board with a unique collection of
     * board placements
     */
    public function createGameBoard(): Collection
    {
        $placedBoats = collect([]);
        $boardSize = config('battleship.size');
        $board = collect([]);
        $boats = Boat::all();
        $boats->each(function ($boat) use ($board, $boardSize, $placedBoats) {
            $heatmap = (new HeatmapService($this->partie, $placedBoats))->createHeatmap()
                ->generateAll()
                ->filter(fn ($weight) => $weight > 0)
                ->sort()
                ->take(config('battleship.placement.size'));

            do {
                $startOfShip = Vector::from($heatmap->keys()->random());
                $direction = Vector::directions()->random();
                $endOfShipVector = $startOfShip->add(
                    $direction->mult($boat->size)
                );
                $middleOfShip = $startOfShip->copy();
                $hasMiddleOfShipInPlacement = false;
                while ($middleOfShip->equals($endOfShipVector)) {
                    $middleOfShip = $middleOfShip->add($direction);
                    if ($placedBoats->contains($middleOfShip->str())) {
                        $hasMiddleOfShipInPlacement = true;
                        break;
                    }
                }
            } while (
                ! $endOfShipVector->within(0, $boardSize - 1) ||
                $placedBoats->contains($startOfShip) || $placedBoats->contains($endOfShipVector) ||
                $hasMiddleOfShipInPlacement
            );

            $collection = collect([]);
            $middleOfShip = $startOfShip->copy();
            for ($i = 0; $i < $boat->size; $i++) {
                $middleOfShip = $middleOfShip->add($direction);
                $collection->add($middleOfShip->str());
                $placedBoats->add($middleOfShip->str());
            }
            $board->put($boat->name, $collection);
        });

        if ($placedBoats->unique()->count() !== $placedBoats->count()) {
            return $this->createGameBoard();
        }

        return $board;
    }
}
