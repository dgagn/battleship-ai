<?php

namespace App\Ai;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * The heatmap is a representation of coordinates based on a
 * certain weight. It uses Probability Density to calculate the
 * weight of boats on the battleship board. Not using any eloquent
 * models. There's no dependency other than the config to make
 * sure we can customize the weighting with a config file.
 *
 * @see Config
 * @author Dany Gagnon
 */
class Heatmap
{
    /** @var Collection everything excluded from the heatmap calculation. */
    private Collection $excludedFromHeatmap;

    /** @var Collection the remaining boat size left. */
    private Collection $boatSizes;

    /** @var Collection the collection representing the stacks. */
    private Collection $stacks;

    /** @var Collection the old shots */
    private Collection $oldShots;

    /**
     * Constructs a heatmap that will weight all the coordinates
     * on a grid for battleship.
     *
     * @param Collection $excludedFromHeatmap the shots or square
     * to exclude
     * @param Collection $boatSizes the remaining boat sizes
     * @param Collection $stacks the stack to be able to
     * artificially weight the directions of missiles that hit.
     */
    public function __construct(Collection $excludedFromHeatmap, Collection $boatSizes, Collection $stacks, Collection $oldShots)
    {
        $this->excludedFromHeatmap = $excludedFromHeatmap;
        $this->boatSizes = $boatSizes;
        $this->stacks = $stacks;
        $this->oldShots = $oldShots;
    }

    /**
     * Returns the heaviest coordinate based on the coordinates
     * weight created from adding all the weights together,
     * artificially augmenting the stack and with a heatmap
     * of all the remaining boats. The parity makes sure the
     * coordinate of a vector can skip a certain amount of cells.
     * The partity is calculated by the smallest boat. So if
     * for exemple, a boat as a size of 2, i will shoot every
     * 2 squares. However, if the smallest boat has a size of
     * 3, I will shoot every (X + Y) % 3 === 0, so the boat has
     * more chances of getting hit.
     *
     * the collection for the shots fire during the game. the
     * sizes of the remaining boats and the stack of coordinates
     * from the directions of a hit shot.
     *
     * @return string the heaviest coordinate based on the
     * coordinates weight created from adding all the weights
     * together, artificially augmenting the stack and with a
     * heatmap of all the remaining boats.
     */
    public function heaviestCoordinateWithParity(): string
    {
        $heatmap = $this->generateAll();
        $parity = $this->boatSizes->sort()->first();
        $weightShot = config('battleship.weighting.shot');

        foreach ($heatmap as $coordinate => $weighting) {
            if (! $this->excludedFromHeatmap->contains($coordinate)) {
                $heatmap->put($coordinate, $weighting + $weightShot);
            }
        }

        $highestWeighting = $heatmap->sortDesc()->keys()
            ->skipUntil(
                fn ($coordinate) => Vector::from($coordinate)->parity($parity)
            )->first();

        return $highestWeighting ?: $heatmap->sortDesc()->keys()->first();
    }

    /**
     * Returns the heaviest coordinate based on the coordinates
     * weight created from adding all the weights together,
     * artificially augmenting the stack and with a heatmap
     * of all the remaining boats.
     *
     * the collection for the shots fire during the game, the
     * sizes of the remaining boats and the stack of coordinates
     * from the directions of a hit shot.
     *
     * @return string the heaviest coordinate based on the
     * coordinates weight created from adding all the weights
     * together, artificially augmenting the stack and with a
     * heatmap of all the remaining boats.
     */
    public function heaviestCoordinateWithStacks(): string
    {
        $heatmap = $this->generateWithStacks();

        return $heatmap->sortDesc()->keys()->first();
    }

    /**
     * Returns a collection with a collection of sizes but also
     * includes the stack, so it makes sure to artificially
     * weight the 4 directions of a shot. The weighting is a
     * addition of the weight also added by the stack algorithm,
     * so it makes sure to shoot in the correct directions.
     *
     * the shots to exclude from the heatmap, the sizes of all
     * the remaining boats and the stacks value represented
     * as coordinate, weighting.
     *
     * @return Collection a collection with a collection of
     * sizes but also includes the stack
     */
    public function generateWithStacks(): Collection
    {
        $stackWeight = config('battleship.weighting.stack');
        $weightNone = config('battleship.weighting.none');
        $heatmap = $this->generateAll()->filter(fn ($weight) => $weight !== $weightNone);

        foreach ($heatmap as $coordinate => $weighting) {
            if ($this->stacks->get($coordinate) > $weightNone && ! $this->excludedFromHeatmap->contains($coordinate)) {
                $heatmap->put($coordinate, $weighting + $stackWeight + ($this->stacks->get($coordinate) ?? 0));
            }
        }

        return $heatmap;
    }

    /**
     * Returns a collection with all the sizes merged into a
     * single heatmap. This is the combination of all the sizes
     * of the boats combined to give the most accurate weighting
     * based on the sizes of all the remaining boats.
     *
     * the shots to exclude from the calculation of the heatmap
     * and the sizes of the remaining boats.
     *
     * @return Collection the collection with all the sizes
     * merged to a single heatmap
     */
    public function generateAll(): Collection
    {
        $heatmaps = collect([]);
        $this->boatSizes->each(
            function ($size) use ($heatmaps) {
                $heatmapForSize = $this->generate($size);
                foreach ($heatmapForSize as $coord => $weight) {
                    $heatmaps->put($coord, $heatmaps->get($coord) + $weight);
                }
            }
        );

        return $heatmaps;
    }

    /**
     * Returns a heatmap with the generated weighting for a boat
     * with a size of x. It tests all the possibility to place
     * a boat in every direction, and makes sure it is not
     * present in shots.
     *
     * the shots to create a heatmap around. It will make sure
     * no weighting gets added if the boat touches a shot vector
     * and the size of the boat to generate the heatmap for.
     *
     * @return Collection a heatmap with the generated weighting
     * for a boat of a size
     */
    public function generate(int $size): Collection
    {
        $heatmap = $this->empty();
        $boardSize = config('battleship.size');
        $noWeighting = config('battleship.weighting.none');
        $incrementWeighting = config('battleship.weighting.increment');

        foreach ($heatmap as $coordinate => $weighting) {
            foreach (Vector::directions() as $direction) {
                $vector = Vector::from($coordinate);
                $endOfShipVector = $vector->add(
                    $direction->mult($size)
                );
                if (! $endOfShipVector->within(0, $boardSize - 1)) {
                    continue; // ship is out of grid
                }
                $notInShots = $this->excludedFromHeatmap->filter(
                    fn ($shotCoordinate) => $shotCoordinate === $coordinate
                        || $shotCoordinate === $endOfShipVector->str()
                );
                if ($notInShots->count() > 0) {
                    // ensure to remove weighting on shots
                    $notInShots->each(function ($shot) use ($heatmap, $noWeighting) {
                        $heatmap->put($shot, $noWeighting);
                    });
                    continue;
                }

                $hasVectorOverlapping = false;
                $insideOfShip = $vector->copy();
                while (! $insideOfShip->equals($endOfShipVector) && ! $hasVectorOverlapping) {
                    $insideOfShip = $insideOfShip->add($direction);
                    $overlapWithMiddleOfShip = $this->excludedFromHeatmap->filter(
                        fn ($shot) => $shot === $insideOfShip->str()
                    )->count() > 0;
                    if ($overlapWithMiddleOfShip) {
                        $heatmap->put($insideOfShip->str(), $noWeighting);
                        $hasVectorOverlapping = true;
                    }
                }

                if ($hasVectorOverlapping) {
                    continue;
                }

                $vectorCopy = $vector->copy();
                $heatmap->put($coordinate, $weighting + $incrementWeighting);
                while (! $vectorCopy->equals($endOfShipVector)) {
                    $vectorCopy = $vectorCopy->add($direction);
                    $heatmap->put($vectorCopy->str(), $heatmap->get($vectorCopy->str()) + $incrementWeighting);
                }
            }
        }

        return $heatmap;
    }

    /**
     * Returns a collection filled with all the coordinates
     * set with a default weighting.
     *
     * @return Collection a collection filled with all the
     * coordinates set with a default weighting.
     */
    public function empty(): Collection
    {
        $heatmap = collect([]);
        $boardSize = config('battleship.size');
        $defaultWeighting = config('battleship.weighting.default');
        for ($y = 0; $y < $boardSize; $y++) {
            for ($x = 0; $x < $boardSize; $x++) {
                $vec = new Vector($x, $y);
                $heatmap->put($vec->str(), $defaultWeighting);
            }
        }

        return $heatmap;
    }
}
