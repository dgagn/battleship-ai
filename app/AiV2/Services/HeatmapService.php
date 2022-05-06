<?php

namespace App\AiV2\Services;

use App\AiV2\Heatmap;
use App\Models\Boat;
use App\Models\Game;
use Illuminate\Support\Collection;

class HeatmapService
{
    /**
     * @var Game the game associated with the heatmap.
     */
    private Game $partie;

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
     * @param Game $partie the game associated with the creation
     * of the heatmap
     * @param Collection|null $excludedFromHeatmap the collection that is
     * excluded from the heatmap generation.
     */
    public function __construct(Game $partie, Collection $excludedFromHeatmap = null)
    {
        $this->partie = $partie;
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
        $boatSizes = $this->partie->remainingBoats()->get()
            ->map(fn ($boat) => Boat::query()->where('id', $boat->boat_id)->first()->size);
        $shots = $this->excludedFromHeatmap ?? $this->partie->missiles()->get()
            ->map(fn ($missile) => $missile->coordinate);
        $stackCoordinatesWithWeight = $this->partie->stacks()->get()
            ->flatMap(fn ($stack) => [$stack->coordinate => $stack->weight]);

        return new Heatmap($shots, $boatSizes, $stackCoordinatesWithWeight);
    }
}
