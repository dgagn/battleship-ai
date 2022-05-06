<?php

namespace App\Ai\Services;

use App\Ai\Heatmap;
use App\Models\Boat;
use App\Models\Game;
use Illuminate\Support\Collection;

/**
 * The class responsible to get all the required arguments
 * to create a heatmap with all the eloquent models. It makes
 * sure to create the heatmap object with everything it needs.
 *
 * @author Dany Gagnon
 */
class HeatmapService
{
    /**
     * @var Game the game associated with the heatmap.
     */
    private Game $game;

    /**
     * The collection that gets excluded for heatmap generation.
     *
     * @var Collection|null the collection or null will give
     * all the missiles
     */
    private ?Collection $excludedFromHeatmap;

    /**
     * Constructs a heatmap with a game and a collection that
     * says whether I should calculate the coordinate
     * or remove it entirely.
     *
     * @param Game $game the game associated with the creation
     * of the heatmap
     * @param Collection|null $excludedFromHeatmap the collection that is
     * excluded from the heatmap generation.
     */
    public function __construct(Game $game, Collection $excludedFromHeatmap = null)
    {
        $this->game = $game;
        $this->excludedFromHeatmap = $excludedFromHeatmap;
    }

    /**
     * Returns a heatmap generated with all the information
     * that a heatmap needs. It gets all the remaining boat
     * sizes belonging to a game and creates a heatmap based
     * on a lot of factors.
     *
     * @return Heatmap a heatmap generated with all the information
     * that a heatmap needs
     */
    public function createHeatmap(): Heatmap
    {
        $boatSizes = $this->game->remainingBoats()->get()
            ->map(fn ($boat) => Boat::query()->where('id', $boat->boat_id)->first()->size);
        $shots = $this->excludedFromHeatmap ?? $this->game->missiles()->get()
            ->map(fn ($missile) => $missile->coordinate);
        $stackCoordinatesWithWeight = $this->game->stacks()->get()
            ->flatMap(fn ($stack) => [$stack->coordinate => $stack->weight]);

        $oldShots = Game::query()->where('opponent', $this->game->getOpponent())->get();

        $oldShots->map(function ($game) {
            return $game->missiles()->where('result', '>', 0)->get()
                ->map(fn ($missile) => $missile->getCoordinate());
        });

        return new Heatmap($shots, $boatSizes, $stackCoordinatesWithWeight, $oldShots);
    }
}
